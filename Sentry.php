<?php

$sentryRequestId = \MediaWiki\Request\WebRequest::getRequestId();

\Sentry\init( [
	'dsn'                 => $sentryDSN,
	'environment'         => defined( 'MW_ENV' ) ? MW_ENV : 'production',
	'release'             => $wgVersion ?? null,
	'error_types'         => E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR,
	'enable_logs'         => true,
	'traces_sample_rate'  => 1.0,
	'profiles_sample_rate' => 0.3,
	'attach_stacktrace'   => true,
	'max_breadcrumbs'     => 100,
	'before_send_transaction' => static function ( \Sentry\Event $event ) use ( $sentryRequestId ) {
		foreach ( $event->getSpans() as $span ) {
			$span->setTags( [ 'request_id' => $sentryRequestId ] + $span->getTags() );
		}
		return $event;
	},
	'before_send_log' => static function ( \Sentry\Logs\Log $log ) use ( $sentryRequestId ) {
		$log->setAttribute( 'request_id', $sentryRequestId );
		return $log;
	},
] );

\Sentry\configureScope( static function ( \Sentry\State\Scope $scope ) use ( $sentryRequestId ) {
	$scope->setTag( 'request_id', $sentryRequestId );
} );

$wgMWLoggerDefaultSpi = [
	'class' => \MediaWiki\Logger\MonologSpi::class,
	'args'  => [ [
		'loggers' => [
			'exception' => [ 'handlers' => [ 'sentry-logs', 'sentry-crumbs', 'stderr' ], 'processors' => [ 'psr', 'request-id' ] ],
			'error'     => [ 'handlers' => [ 'sentry-logs', 'sentry-crumbs', 'stderr' ], 'processors' => [ 'psr', 'request-id' ] ],
			'DBError'   => [ 'handlers' => [ 'sentry-logs', 'sentry-crumbs', 'stderr' ], 'processors' => [ 'psr', 'request-id' ] ],
			'@default'  => [ 'handlers' => [ 'sentry-logs', 'sentry-crumbs', 'stderr' ], 'processors' => [ 'psr', 'request-id' ] ],
		],
		'processors' => [
			'psr' => [
				'class' => \Monolog\Processor\PsrLogMessageProcessor::class,
				'args'  => [],
			],
			'request-id' => [
				'factory' => static function () {
					return static function ( $record ) {
						$id = \MediaWiki\Request\WebRequest::getRequestId();
						if ( $record instanceof \Monolog\LogRecord ) {
							// @phan-suppress-next-line PhanUndeclaredMethod, PhanUndeclaredProperty
							return $record->with( message: "[$id] " . $record->message );
						}
						$record['message'] = "[$id] " . $record['message'];
						return $record;
					};
				},
			],
		],
		'handlers' => [
			'sentry-logs' => [
				'class' => \Sentry\Monolog\LogsHandler::class,
				'args'  => [ \Sentry\Logs\LogLevel::info() ],
			],
			'sentry-crumbs' => [
				'class' => \Sentry\Monolog\BreadcrumbHandler::class,
				'args'  => [ \Sentry\SentrySdk::getCurrentHub(), \Monolog\Logger::DEBUG ],
			],
			'stderr' => [
				'class'     => \Monolog\Handler\StreamHandler::class,
				'args'      => [ 'php://stderr', \Monolog\Logger::DEBUG ],
				'formatter' => 'line',
			],
		],
		'formatters' => [
			'line' => [
				'class' => \Monolog\Formatter\LineFormatter::class,
				'args'  => [ null, null, true, true ],
			],
		],
	] ],
];

if ( PHP_SAPI !== 'cli' ) {
	$sentryTxCtx = new \Sentry\Tracing\TransactionContext();
	$sentryTxCtx->setName( ( $_SERVER['REQUEST_METHOD'] ?? 'GET' ) . ' ' . strtok( $_SERVER['REQUEST_URI'] ?? '/', '?' ) );
	$sentryTxCtx->setOp( 'http.server' );
	$sentryTx = \Sentry\startTransaction( $sentryTxCtx );
	$sentryTx->setTags( [ 'request_id' => $sentryRequestId ] );
	\Sentry\SentrySdk::getCurrentHub()->setSpan( $sentryTx );

	register_shutdown_function( static function () use ( $sentryTx ) {
		try {
			if ( method_exists( $sentryTx, 'setHttpStatus' ) ) {
				$sentryTx->setHttpStatus( http_response_code() ?: 200 );
			}
		} finally {
			$sentryTx->finish();
			\Sentry\flush();
		}
	} );
} else {
	register_shutdown_function( static function () {
		\Sentry\flush();
	} );
}

$wgHooks['LogException'][] = static function ( Throwable $e, bool $suppressed ) {
	if ( !$suppressed ) {
		\Sentry\captureException( $e );
	}
	return true;
};

$wgHooks['UserGetRights'][] = static function ( $user ) {
	if ( $user && $user->isRegistered() ) {
		\Sentry\configureScope( static function ( \Sentry\State\Scope $scope ) use ( $user ) {
			$scope->setUser( [ 'id' => $user->getId(), 'username' => $user->getName() ] );
		} );
	}
	return true;
};

$wgHooks['BeforePageDisplay'][] = static function ( OutputPage $out, Skin $skin ) {
	$out->addHeadItem( 'sentry-js-config', <<<'HTML'
		<script>
		window.sentryOnLoad = function () {
			Sentry.init( {
				beforeSend: function ( event ) {
					var values = event.exception && event.exception.values;
					var error = values && values.length ? values[ values.length - 1 ] : null;
					var frames = error && error.stacktrace && error.stacktrace.frames;
					if ( !frames || !frames.length ) {
						return event;
					}
					if ( error.mechanism && error.mechanism.synthetic ) {
						return event;
					}
					var here = location.href.split( '#' )[ 0 ];
					for ( var i = 0; i < frames.length; i++ ) {
						var filename = String( frames[ i ].filename ).split( '#' )[ 0 ];
						if ( filename !== here && filename !== '<anonymous>' ) {
							return event;
						}
					}
					event.fingerprint = [ 'inline-page-script', error.type || 'Error' ];
					return event;
				}
			} );
		};
		</script>
		HTML );
	$out->addHeadItem( 'sentry-js', '<script src="https://js.sentry-cdn.com/8d12d310c7d40b6b4d8c8989e36a7b5a.min.js" crossorigin="anonymous"></script>' );
	return true;
};

<?php

\Sentry\init( [
	'dsn'                 => $sentryDSN,
	'environment'         => defined( 'MW_ENV' ) ? MW_ENV : 'production',
	'release'             => $wgVersion ?? null,
	'enable_logs'         => true,
	'traces_sample_rate'  => 1.0,
	'profiles_sample_rate' => 0.3,
	'attach_stacktrace'   => true,
	'max_breadcrumbs'     => 100,
] );

$wgMWLoggerDefaultSpi = [
	'class' => \MediaWiki\Logger\MonologSpi::class,
	'args'  => [ [
		'loggers' => [
			'exception' => [ 'handlers' => [ 'sentry-logs', 'sentry-crumbs', 'stderr' ], 'processors' => [ 'psr' ] ],
			'error'     => [ 'handlers' => [ 'sentry-logs', 'sentry-crumbs', 'stderr' ], 'processors' => [ 'psr' ] ],
			'DBError'   => [ 'handlers' => [ 'sentry-logs', 'sentry-crumbs', 'stderr' ], 'processors' => [ 'psr' ] ],
			'@default'  => [ 'handlers' => [ 'sentry-logs', 'sentry-crumbs', 'stderr' ], 'processors' => [ 'psr' ] ],
		],
		'processors' => [
			'psr' => [
				'class' => \Monolog\Processor\PsrLogMessageProcessor::class,
				'args'  => [],
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
	\Sentry\SentrySdk::getCurrentHub()->setSpan( $sentryTx );

	register_shutdown_function( static function () use ( $sentryTx ) {
		try {
			$error = error_get_last();
			if ( $error && in_array( $error['type'], [ E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR ], true ) ) {
				\Sentry\captureMessage( $error['message'], \Sentry\Severity::fatal() );
			}
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
		$error = error_get_last();
		if ( $error && in_array( $error['type'], [ E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR ], true ) ) {
			\Sentry\captureMessage( $error['message'], \Sentry\Severity::fatal() );
		}
		\Sentry\flush();
	} );
}

$wgHooks['LogException'][] = function ( Throwable $e, bool $suppressed ) {
	if ( !$suppressed ) {
		\Sentry\captureException( $e );
	}
	return true;
};

$wgHooks['UserGetRights'][] = function ( $user ) {
	if ( $user && $user->isRegistered() ) {
		\Sentry\configureScope( function ( \Sentry\State\Scope $scope ) use ( $user ) {
			$scope->setUser( [ 'id' => $user->getId(), 'username' => $user->getName() ] );
		} );
	}
	return true;
};

$wgHooks['BeforePageDisplay'][] = function ( OutputPage $out, Skin $skin ) {
	$out->addHeadItem( 'sentry-js', '<script src="https://js.sentry-cdn.com/8d12d310c7d40b6b4d8c8989e36a7b5a.min.js" crossorigin="anonymous"></script>' );
	return true;
};

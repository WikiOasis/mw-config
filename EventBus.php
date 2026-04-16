<?php

use MediaWiki\Extension\EventBus\Adapters\JobQueue\JobQueueEventBus;
use MediaWiki\Extension\EventBus\Adapters\RCFeed\EventBusRCFeedEngine;
use MediaWiki\Extension\EventBus\Adapters\RCFeed\EventBusRCFeedFormatter;
use Wikimedia\EventRelayer\EventRelayerNull;

$wgEnableEventBus = 'TYPE_ALL';

if ( $cwPrivate ) {
	$wgEnableEventBus = 'TYPE_JOB|TYPE_EVENT';
}

$wgEventServiceDefault = 'eventgate';

$wgEventServices = [
	'eventgate' => [
		'url' => 'http://eventgate41:8192/v1/events',
		'timeout' => 62,
	],
];

$wgEventStreams = [
	'mediawiki.recentchange' => [
		'schema_title' => 'mediawiki/recentchange',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.page_change.v1' => [
		'schema_title' => 'mediawiki/page/change',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'resource_change' => [
		'schema_title' => 'resource_change',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.page-create' => [
		'schema_title' => 'mediawiki/revision/create',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.page-delete' => [
		'schema_title' => 'mediawiki/page/delete',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.page-suppress' => [
		'schema_title' => 'mediawiki/page/delete',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.page-undelete' => [
		'schema_title' => 'mediawiki/page/undelete',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.page-move' => [
		'schema_title' => 'mediawiki/page/move',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.page-properties-change' => [
		'schema_title' => 'mediawiki/page/properties-change',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.page-links-change' => [
		'schema_title' => 'mediawiki/page/links-change',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.page-restrictions-change' => [
		'schema_title' => 'mediawiki/page/restrictions-change',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.centralnotice.campaign-create' => [
		'schema_title' => 'mediawiki/centralnotice/campaign/create',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.centralnotice.campaign-change' => [
		'schema_title' => 'mediawiki/centralnotice/campaign/change',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
	'mediawiki.centralnotice.campaign-delete' => [
		'schema_title' => 'mediawiki/centralnotice/campaign/delete',
		'producers' => [
			'mediawiki_eventbus' => [
				'event_service_name' => 'eventgate',
			],
		],
	],
    '/^mediawiki\\.job\\..+/' => [
		'schema_title' => 'mediawiki/job',
		'destination_event_service' => 'eventgate',
		'canary_events_enabled' => false,
	],
];

$wgRCFeeds['eventgate'] = [
	'class' => EventBusRCFeedEngine::class,
	'formatter' => EventBusRCFeedFormatter::class,
	'eventServiceName' => 'eventgate',
];

$wgEventRelayerConfig = [
	'default' => [
		'class' => EventRelayerNull::class,
	],
];

$wgEventBusEnableRunJobAPI =
	wfHostname() === 'mwtask11' ||
	wfHostname() === 'staging11';

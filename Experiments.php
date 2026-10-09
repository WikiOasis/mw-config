<?php

$wgWikiOasisMagicCentralWiki = 'metawiki';
$wgWikiOasisMagicExperimentsDashboardUrl = 'https://grafana.wikioasis.org/d/wikioasis-experiments/wikioasis-experiments?var-experiment={experiment}';

$wgWikiOasisMagicExperiments['new_onboarding'] = [
    'label' => 'New onboarding on a share of wikis',
    'description' => 'Switches $wgWikiOasisMagicEnableNewOnboarding on for a share of wikis to test an improved signup flow.',
    'owner' => 'Zippybonzo',
    'unit' => 'wiki',
    'active' => true,
    'rollout' => 100,
    'default' => 'control',
    'variants' => [
        'control' => [ 'weight' => 50, 'config' => [ 'wgWikiOasisMagicEnableNewOnboarding' => false ] ],
        'treatment' => [ 'weight' => 50, 'config' => [ 'wgWikiOasisMagicEnableNewOnboarding' => true ] ],
    ],
    'salt' => 'new-onboarding-1',
    'metrics' => [
        'signups' => [ 'type' => 'log', 'log' => 'newusers/create', 'label' => 'Accounts created' ],
        'edits' => [ 'type' => 'edit', 'label' => 'Edits' ],
        'welcome' => [ 'type' => 'specialpage', 'page' => 'Welcome', 'label' => 'Opened Special:Welcome' ],
    ],
    'primary' => 'signups',
];
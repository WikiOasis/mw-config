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

$wgWikiOasisMagicExperiments['edit_prompt'] = [
    'label' => 'Edit-this-page popout',
    'description' => 'A small popout on the edit tab telling readers who have never edited that they can edit the page.',
    'owner' => 'Zippybonzo',
    'unit' => 'browser',
    'preview' => 'Main Page',
    'active' => false,
    'rollout' => 100,
    'default' => 'control',
    'variants' => [
        'control' => 50,
        'treatment' => 50,
    ],
    'salt' => 'edit-prompt-1',
    'window' => 30,
    'metrics' => [
        'content_edit' => [ 'type' => 'edit', 'namespaces' => [ 0 ], 'label' => 'Edited an article' ],
        'edit' => [ 'type' => 'edit', 'label' => 'Made any edit' ],
        've_edit' => [ 'type' => 'tag', 'tags' => [ 'visualeditor' ], 'label' => 'Edited with VisualEditor' ],
        'edit_click' => [ 'type' => 'event', 'client' => true, 'label' => 'Clicked an edit tab or section edit link' ],
        'prompt_shown' => [ 'type' => 'event', 'client' => true, 'label' => 'Saw the popout' ],
        'prompt_click' => [ 'type' => 'event', 'client' => true, 'label' => 'Clicked "Edit" in the popout' ],
        'prompt_dismiss' => [ 'type' => 'event', 'client' => true, 'label' => 'Dismissed the popout' ],
    ],
    'primary' => 'content_edit',
];

$wgWikiOasisMagicExperiments['wiki_prompt'] = [
    'label' => 'Start-a-wiki popout',
    'description' => 'A small card on a logged-in user\'s first eligible page view that invites them to start their own wiki on Special:RequestWiki. Shown once per user across the farm; users who already requested a wiki are counted as ineligible.',
    'owner' => 'Zippybonzo',
    'unit' => 'user',
    'preview' => 'Main Page',
    'population' => 'all',
    'active' => false,
    'rollout' => 100,
    'default' => 'control',
    'variants' => [
        'control' => 50,
        'treatment' => 50,
    ],
    'salt' => 'wiki-prompt-1',
    'window' => 30,
    'metrics' => [
        'wiki_request' => [ 'type' => 'log', 'log' => 'farmer/requestwiki', 'label' => 'Requested a wiki' ],
        'request_page' => [ 'type' => 'specialpage', 'page' => 'RequestWiki', 'label' => 'Opened Special:RequestWiki' ],
        'prompt_shown' => [ 'type' => 'event', 'client' => true, 'label' => 'Saw the popout' ],
        'prompt_click' => [ 'type' => 'event', 'client' => true, 'label' => 'Clicked "Start a wiki"' ],
        'prompt_dismiss' => [ 'type' => 'event', 'client' => true, 'label' => 'Dismissed the popout' ],
    ],
    'primary' => 'wiki_request',
];
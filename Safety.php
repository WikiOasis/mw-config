<?php

$wgWikiOasisSafetyWizard = [
    'showProgress' => false,
];

$wgWikiOasisSafetySteps = [
    'triage' => [
        'title-message' => 'wikioasissafety-flow-triage-title',
        'description-message' => 'wikioasissafety-flow-triage-description',
        'branches' => [
            [
                'when' => [ 'field' => 'help', 'op' => 'equals', 'value' => 'unacceptable' ],
                'goTo' => 'guidance',
            ],
        ],
        'fields' => [
            [
                'type' => 'radio',
                'name' => 'help',
                'label-message' => 'wikioasissafety-flow-help-label',
                'required' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-help-unacceptable',
                        'value' => 'unacceptable',
                        'description-message' => 'wikioasissafety-flow-help-unacceptable-description',
                    ],
                ],
            ],
            [
                'type' => 'radio',
                'name' => 'report',
                'label-message' => 'wikioasissafety-flow-report-label',
                'required' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-report-threat',
                        'value' => 'threat-of-physical-harm',
                        'description-message' => 'wikioasissafety-flow-report-threat-description',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-report-licensing',
                        'value' => 'a-licensing-issue',
                        'description-message' => 'wikioasissafety-flow-report-licensing-description',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-report-child',
                        'value' => 'a-child-protection-issue',
                        'description-message' => 'wikioasissafety-flow-report-child-description',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-report-underage',
                        'value' => 'underage-user',
                        'description-message' => 'wikioasissafety-flow-report-underage-description',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-report-harassment',
                        'value' => 'harassment',
                        'description-message' => 'wikioasissafety-flow-report-harassment-description',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-report-other',
                        'value' => 'something-else',
                        'followUp' => [
                            'type' => 'text',
                            'name' => 'report-other',
                            'label-message' => 'wikioasissafety-flow-report-other-followup',
                            'required' => true,
                        ],
                    ],
                ],
            ],
        ],
    ],
    'report-subject' => [
        'title-message' => 'wikioasissafety-flow-subject-title',
        'fields' => [
            [
                'type' => 'checkboxGroup',
                'name' => 'concerns',
                'label-message' => 'wikioasissafety-flow-concerns-label',
                'required' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-concerns-pages',
                        'value' => 'pages',
                        'description-message' => 'wikioasissafety-flow-concerns-pages-description',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-concerns-users',
                        'value' => 'users',
                        'description-message' => 'wikioasissafety-flow-concerns-users-description',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-concerns-wikis',
                        'value' => 'wikis',
                        'description-message' => 'wikioasissafety-flow-concerns-wikis-description',
                    ],
                ],
            ],
            [
                'type' => 'chipInput',
                'name' => 'pages',
                'label-message' => 'wikioasissafety-flow-pages-label',
                'description-message' => 'wikioasissafety-flow-pages-description',
                'visibleWhen' => [ 'field' => 'concerns', 'op' => 'contains', 'value' => 'pages' ],
                'required' => true,
                'search' => 'pages',
                'searchNamespaces' => [ 0, 1, 2, 3, 4, 5 ],
            ],
            [
                'type' => 'chipInput',
                'name' => 'users',
                'label-message' => 'wikioasissafety-flow-users-label',
                'description-message' => 'wikioasissafety-flow-users-description',
                'visibleWhen' => [ 'field' => 'concerns', 'op' => 'contains', 'value' => 'users' ],
                'required' => true,
                // Registered accounts on this wiki, via allusers.
                'search' => 'users',
            ],
            [
                'type' => 'chipInput',
                'name' => 'wikis',
                'label-message' => 'wikioasissafety-flow-wikis-label',
                'description-message' => 'wikioasissafety-flow-wikis-description',
                'visibleWhen' => [ 'field' => 'concerns', 'op' => 'contains', 'value' => 'wikis' ],
                'required' => true,
                'search' => 'list',
                'searchOptions' => array_map(
                    static fn ( string $db ): string => preg_replace( '/wiki$/', '', $db )
                        . '.wikioasis.org',
                    $wgLocalDatabases
                ),
            ],
        ],
    ],

    'report-details' => [
        'title-message' => 'wikioasissafety-flow-details-title',
        'fields' => [
            [
                'type' => 'textarea',
                'name' => 'details',
                'label-message' => 'wikioasissafety-flow-details-label',
                'description-message' => 'wikioasissafety-flow-details-description',
                'required' => true,
                'autosize' => true,
                'rows' => 4,
            ],

            // -- Threat of physical harm
            [
                'type' => 'radio',
                'name' => 'threat-immediacy',
                'label-message' => 'wikioasissafety-flow-threat-immediacy-label',
                'description-message' => 'wikioasissafety-flow-threat-immediacy-description',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'threat-of-physical-harm',
                ],
                'optional' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-threat-immediacy-now',
                        'value' => 'now',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-threat-immediacy-threatened',
                        'value' => 'threatened',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-threat-immediacy-happened',
                        'value' => 'happened',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-unsure',
                        'value' => 'unsure',
                    ],
                ],
            ],
            [
                'type' => 'radio',
                'name' => 'threat-authorities',
                'label-message' => 'wikioasissafety-flow-threat-authorities-label',
                'description-message' => 'wikioasissafety-flow-threat-authorities-description',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'threat-of-physical-harm',
                ],
                'optional' => true,
                'inline' => true,
                'options' => [
                    [ 'label-message' => 'wikioasissafety-flow-yes', 'value' => 'yes' ],
                    [ 'label-message' => 'wikioasissafety-flow-no', 'value' => 'no' ],
                    [ 'label-message' => 'wikioasissafety-flow-unsure', 'value' => 'unsure' ],
                ],
            ],
            [
                'type' => 'text',
                'name' => 'threat-where',
                'label-message' => 'wikioasissafety-flow-threat-where-label',
                'description-message' => 'wikioasissafety-flow-threat-where-description',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'threat-of-physical-harm',
                ],
                'optional' => true,
            ],

            // -- Licensing issue
            [
                'type' => 'radio',
                'name' => 'licensing-standing',
                'label-message' => 'wikioasissafety-flow-licensing-standing-label',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'a-licensing-issue',
                ],
                'optional' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-licensing-standing-holder',
                        'value' => 'rights-holder',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-licensing-standing-agent',
                        'value' => 'agent',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-licensing-standing-neither',
                        'value' => 'neither',
                    ],
                ],
            ],
            [
                'type' => 'text',
                'name' => 'licensing-source',
                'label-message' => 'wikioasissafety-flow-licensing-source-label',
                'description-message' => 'wikioasissafety-flow-licensing-source-description',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'a-licensing-issue',
                ],
                'optional' => true,
            ],

            // -- Child protection
            [
                'type' => 'message',
                'name' => 'child-notice',
                'text-message' => 'wikioasissafety-flow-child-notice',
                'messageType' => 'warning',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'a-child-protection-issue',
                ],
            ],
            [
                'type' => 'radio',
                'name' => 'child-nature',
                'label-message' => 'wikioasissafety-flow-child-nature-label',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'a-child-protection-issue',
                ],
                'optional' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-child-nature-content',
                        'value' => 'sexual-content',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-child-nature-contact',
                        'value' => 'contact',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-child-nature-personal',
                        'value' => 'personal-information',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-child-nature-other',
                        'value' => 'other',
                    ],
                ],
            ],
            [
                'type' => 'radio',
                'name' => 'child-authorities',
                'label-message' => 'wikioasissafety-flow-child-authorities-label',
                'description-message' => 'wikioasissafety-flow-child-authorities-description',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'a-child-protection-issue',
                ],
                'optional' => true,
                'inline' => true,
                'options' => [
                    [ 'label-message' => 'wikioasissafety-flow-yes', 'value' => 'yes' ],
                    [ 'label-message' => 'wikioasissafety-flow-no', 'value' => 'no' ],
                    [ 'label-message' => 'wikioasissafety-flow-unsure', 'value' => 'unsure' ],
                ],
            ],

            // -- Underage
            [
                'type' => 'message',
                'name' => 'underage-notice',
                'text-message' => 'wikioasissafety-flow-underage-notice',
                'messageType' => 'warning',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'underage-user',
                ],
            ],
            [
                'type' => 'radio',
                'name' => 'underage-basis',
                'label-message' => 'wikioasissafety-flow-underage-basis-label',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'underage-user',
                ],
                'required' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-underage-basis-onwiki',
                        'value' => 'said-on-wiki',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-underage-basis-elsewhere',
                        'value' => 'said-elsewhere',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-underage-basis-someone',
                        'value' => 'third-party',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-underage-basis-other',
                        'value' => 'other',
                        'followUp' => [
                            'type' => 'text',
                            'name' => 'underage-basis-other',
                            'label-message' => 'wikioasissafety-flow-underage-basis-other-followup',
                            'required' => true,
                        ],
                    ],
                ],
            ],
            [
                'type' => 'text',
                'name' => 'underage-where',
                'label-message' => 'wikioasissafety-flow-underage-where-label',
                'description-message' => 'wikioasissafety-flow-underage-where-description',
                'visibleWhen' => [
                    'field' => 'report', 'op' => 'equals', 'value' => 'underage-user',
                ],
                'optional' => true,
            ],

            // -- Harassment
            [
                'type' => 'radio',
                'name' => 'harassment-target',
                'label-message' => 'wikioasissafety-flow-harassment-target-label',
                'visibleWhen' => [ 'field' => 'report', 'op' => 'equals', 'value' => 'harassment' ],
                'optional' => true,
                'inline' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-harassment-target-me',
                        'value' => 'me',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-harassment-target-other',
                        'value' => 'someone-else',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-harassment-target-several',
                        'value' => 'several',
                    ],
                ],
            ],
            [
                'type' => 'checkboxGroup',
                'name' => 'harassment-where',
                'label-message' => 'wikioasissafety-flow-harassment-where-label',
                'description-message' => 'wikioasissafety-flow-harassment-where-description',
                'visibleWhen' => [ 'field' => 'report', 'op' => 'equals', 'value' => 'harassment' ],
                'optional' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-harassment-where-thiswiki',
                        'value' => 'this-wiki',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-harassment-where-otherwiki',
                        'value' => 'another-wiki',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-harassment-where-offwiki',
                        'value' => 'off-wiki',
                    ],
                ],
            ],
            [
                'type' => 'radio',
                'name' => 'harassment-ongoing',
                'label-message' => 'wikioasissafety-flow-harassment-ongoing-label',
                'visibleWhen' => [ 'field' => 'report', 'op' => 'equals', 'value' => 'harassment' ],
                'optional' => true,
                'inline' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-harassment-ongoing-yes',
                        'value' => 'ongoing',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-harassment-ongoing-stopped',
                        'value' => 'stopped',
                    ],
                    [ 'label-message' => 'wikioasissafety-flow-unsure', 'value' => 'unsure' ],
                ],
            ],

            // -- Evidence
            [
                'type' => 'fileUpload',
                'name' => 'attachments',
                'label-message' => 'wikioasissafety-flow-attachments-label',
                'optional' => true,
                'accept' => 'image/*,application/pdf',
                'multiple' => true,
                'maxFiles' => 10,
                'maxSizeMb' => 10,
                'showThumbnails' => true,
            ],
        ],
    ],

    'report-final' => [
        'title-message' => 'wikioasissafety-flow-final-title',
        'isFinal' => true,
        'fields' => [
            [
                'type' => 'checkbox',
                'name' => 'threat-to-life',
                'label-message' => 'wikioasissafety-flow-threattolife-label',
                'category' => 'threat-of-physical-harm',
            ],
            [
                'type' => 'toggle',
                'name' => 'anonymous',
                'label-message' => 'wikioasissafety-flow-anonymous-label',
                'description-message' => 'wikioasissafety-flow-anonymous-description',
                'alignSwitch' => true,
            ],
        ],
    ],

    'guidance' => [
        'title-message' => 'wikioasissafety-flow-guidance-title',
        'nextLabel-message' => 'wikioasissafety-flow-guidance-next',
        'backLabel-message' => 'wikioasissafety-flow-guidance-back',
        'isFinal' => true,
        'nextAction' => 'default',
        'fields' => [
            [
                'type' => 'card',
                'name' => 'sockpuppetry',
                'label-message' => 'wikioasissafety-flow-guidance-sockpuppetry',
                'description-message' => 'wikioasissafety-flow-guidance-sockpuppetry-description',
                'startIcon' => 'cdxIconUserGroup',
            ],
            [
                'type' => 'card',
                'name' => 'spam',
                'label-message' => 'wikioasissafety-flow-guidance-spam',
                'description-message' => 'wikioasissafety-flow-guidance-spam-description',
                'startIcon' => 'cdxIconTrash',
            ],
            [
                'type' => 'card',
                'name' => 'vandalism',
                'label-message' => 'wikioasissafety-flow-guidance-vandalism',
                'description-message' => 'wikioasissafety-flow-guidance-vandalism-description',
                'startIcon' => 'cdxIconFlag',
            ],
        ],
    ],
];

$wgWikiOasisSafetyExclusiveFields = [
    [ 'help', 'report' ],
];

$wgWikiOasisSafetyFieldRoles = [
    'concerns' => 'concerns',
    'pages' => 'pages',
    'users' => 'users',
    'wikis' => 'wikis',
    'details' => 'details',
];

$wgWikiOasisSafetyDataWizard = [
    'title-message' => 'wikioasissafety-flow-data-title',
    'submitLabel-message' => 'wikioasissafety-flow-data-submit',
    'showProgress' => true,
];

$wgWikiOasisSafetyDataSteps = [
    'kind' => [
        'title-message' => 'wikioasissafety-flow-data-kind-title',
        'description-message' => 'wikioasissafety-flow-data-kind-description',
        'branches' => [
            [
                'when' => [ 'field' => 'request', 'op' => 'equals', 'value' => 'erase' ],
                'goTo' => 'erase',
            ],
        ],
        'fields' => [
            [
                'type' => 'radio',
                'name' => 'request',
                'label-message' => 'wikioasissafety-flow-data-request-label',
                'required' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-data-request-erase',
                        'value' => 'erase',
                        'description-message' => 'wikioasissafety-flow-data-request-erase-description',
                    ],
                ],
            ],
        ],
    ],

    'erase' => [
        'title-message' => 'wikioasissafety-flow-data-erase-title',
        'description-message' => 'wikioasissafety-flow-data-erase-scope-intro',
        'isFinal' => true,
        'fields' => [
            [
                'type' => 'checkboxGroup',
                'name' => 'erase-scope',
                'label-message' => 'wikioasissafety-flow-data-erase-scope-label',
                'required' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-data-erase-scope-mediawiki',
                        'value' => 'mediawiki',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-data-erase-scope-other',
                        'value' => 'other',
                        'followUp' => [
                            'name' => 'erase-other',
                            'label-message' => 'wikioasissafety-flow-data-erase-scope-other-followup',
                            'required' => true,
                        ],
                    ],
                ],
            ],
            [
                'type' => 'textarea',
                'name' => 'erase-details',
                'label-message' => 'wikioasissafety-flow-data-more-label',
                'optional' => true,
                'rows' => 3,
            ],
            [
                'type' => 'checkbox',
                'name' => 'erase-confirm',
                'label-message' => 'wikioasissafety-flow-data-erase-scope-confirm',
                'required' => true,
            ],
        ],
    ],
];

$wgWikiOasisSafetyDataExclusiveFields = [];
$wgWikiOasisSafetyDataFieldRoles = [];

$wgWikiOasisSafetyContactWizard = [
    'title-message' => 'wikioasissafety-flow-contact-title',
    'submitLabel-message' => 'wikioasissafety-flow-contact-submit',
    'showProgress' => true,
];

$wgWikiOasisSafetyContactSteps = [
    'reason' => [
        'title-message' => 'wikioasissafety-flow-contact-reason-title',
        'branches' => [
            [
                'when' => [ 'field' => 'contact-reason', 'op' => 'equals', 'value' => 'appeal' ],
                'goTo' => 'appeal',
            ],
        ],
        'fields' => [
            [
                'type' => 'radio',
                'name' => 'contact-reason',
                'label-message' => 'wikioasissafety-flow-contact-reason-label',
                'required' => true,
                'options' => [
                    [
                        'label-message' => 'wikioasissafety-flow-contact-reason-general',
                        'value' => 'general',
                        'description-message' => 'wikioasissafety-flow-contact-reason-general-description',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-contact-reason-appeal',
                        'value' => 'appeal',
                        'description-message' => 'wikioasissafety-flow-contact-reason-appeal-description',
                    ],
                    [
                        'label-message' => 'wikioasissafety-flow-contact-reason-other',
                        'value' => 'other',
                        'description-message' => 'wikioasissafety-flow-contact-reason-other-description',
                    ],
                ],
            ],
        ],
    ],

    'message' => [
        'title-message' => 'wikioasissafety-flow-contact-message-title',
        'isFinal' => true,
        'fields' => [
            [
                'type' => 'text',
                'name' => 'subject',
                'label-message' => 'wikioasissafety-flow-contact-subject-label',
                'required' => true,
            ],
            [
                'type' => 'textarea',
                'name' => 'message',
                'label-message' => 'wikioasissafety-flow-contact-message-label',
                'required' => true,
                'rows' => 6,
            ],
            [
                'type' => 'fileUpload',
                'name' => 'attachments',
                'label-message' => 'wikioasissafety-flow-contact-attachments-label',
                'optional' => true,
                'maxFiles' => 5,
            ],
        ],
    ],

    'appeal' => [
        'title-message' => 'wikioasissafety-flow-contact-appeal-title',
        'description-message' => 'wikioasissafety-flow-contact-appeal-description',
        'isFinal' => true,
        'fields' => [
            [
                'type' => 'select',
                'name' => 'appeal-target',
                'label-message' => 'wikioasissafety-flow-contact-appeal-target-label',
                'required' => true,
                'defaultLabel-message' => 'wikioasissafety-flow-contact-appeal-target-default',
                'optionsFrom' => 'sanctions',
            ],
            [
                'type' => 'textarea',
                'name' => 'appeal-grounds',
                'label-message' => 'wikioasissafety-flow-contact-appeal-grounds-label',
                'description-message' => 'wikioasissafety-flow-contact-appeal-grounds-description',
                'required' => true,
                'rows' => 6,
            ],
            [
                'type' => 'fileUpload',
                'name' => 'appeal-attachments',
                'label-message' => 'wikioasissafety-flow-contact-attachments-label',
                'optional' => true,
                'maxFiles' => 5,
            ],
        ],
    ],
];

$wgWikiOasisSafetyContactExclusiveFields = [];
$wgWikiOasisSafetyContactFieldRoles = [];

$wgWikiOasisSafetyCategories = [
    'threat-of-physical-harm' => [
        'label-message' => 'wikioasissafety-category-threat-of-physical-harm',
        'group' => 'harm',
        'description-message' => 'wikioasissafety-category-threat-of-physical-harm-description',
    ],
    'a-child-protection-issue' => [
        'label-message' => 'wikioasissafety-category-a-child-protection-issue',
        'group' => 'harm',
    ],
    'harassment' => [
        'label-message' => 'wikioasissafety-category-harassment',
        'group' => 'conduct',
    ],
    'a-licensing-issue' => [
        'label-message' => 'wikioasissafety-category-a-licensing-issue',
        'group' => 'content',
    ],
    'underage-user' => [
        'label-message' => 'wikioasissafety-category-underage-user',
        'group' => 'eligibility',
    ],
    'something-else' => [
        'label-message' => 'wikioasissafety-category-something-else',
        'group' => 'other',
    ],
    'unacceptable' => [
        'label-message' => 'wikioasissafety-category-unacceptable',
        'group' => 'conduct',
    ],
];

$wgWikiOasisSafetyDataCategories = [
    'access' => [
        'label-message' => 'wikioasissafety-category-data-access',
        'group' => 'data',
    ],
    'erase' => [
        'label-message' => 'wikioasissafety-category-data-erasure',
        'group' => 'data',
    ],
];

$wgWikiOasisSafetyContactCategories = [
    'general' => [
        'label-message' => 'wikioasissafety-category-contact-general',
        'group' => 'enquiry',
    ],
    'appeal' => [
        'label-message' => 'wikioasissafety-category-contact-appeal',
        'group' => 'appeal',
    ],
    'other' => [
        'label-message' => 'wikioasissafety-category-contact-other',
        'group' => 'other',
    ],
];

$wgWikiOasisSafetyEnabled = true;
$wgWikiOasisSafetyNoticeboard = 'm:Steward requests';
$wgWikiOasisSafetyPortalUrl = 'https://safety.wikioasis.org';


$wgRateLimits['wikioasissafety-report-anon']['ip'] = [ 5000, 5 ];
$wgRateLimits['wikioasissafety-report']['ip'] = [ 5000, 5 ];

$wgWikiOasisSafetyMaxUploadSize = 25 * 1024 * 1024;
$wgWikiOasisSafetyCheckUserTracking = true;
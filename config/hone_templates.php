<?php

use App\Controls\MoltinCollectionSelection;

return [

    // nicer display names for templates
    'nice_template_names' => [
        'pages' => [
            'home' => 'Home',
            'about' => 'About',
            'info' => 'Info',
            'two_column_50_50' => 'Two column - 50/50',
        ],
    ],

    // additional fields config
    'additional_fields' => [
        'pages' => [
            'home' => [
                'banner_image' => [
                    'type' => 'image',
                    'label' => 'Banner: Image',
                ],
                'banner_icon' => [
                    'type' => 'image',
                    'label' => 'Banner: Icon',
                ],
                'banner_header' => [
                    'type' => 'text',
                    'label' => 'Banner: Header',
                ],
                'banner_body' => [
                    'type' => 'textarea',
                    'label' => 'Banner: Body',
                ],
                'banner_cta_link' => [
                    'type' => 'text',
                    'label' => 'Banner: Call To Action URI',
                ],
                'banner_cta_text' => [
                    'type' => 'text',
                    'label' => 'Banner: Call To Action Text',
                ],
                'promotional_collection' => [
                    'type' => 'dropdown',
                    'label' => 'Promotional Collection',
                    'handler' => MoltinCollectionSelection::class,
                ],
                'promotional_collection_subtitle' => [
                    'type' => 'text',
                    'label' => 'Promotional Collection Subtitle',
                ],
                'about_icon' => [
                    'type' => 'image',
                    'label' => 'About: Icon',
                ],
                'about_tagline' => [
                    'type' => 'textarea',
                    'label' => 'About: Tagline',
                ],
                'about_left_column' => [
                    'type' => 'textarea',
                    'label' => 'About: Left Column',
                ],
                'about_right_column' => [
                    'type' => 'textarea',
                    'label' => 'About: Right Column',
                ],
            ],
            'info' => [
                'banner_image' => [
                    'type' => 'image',
                    'label' => 'Banner: Image',
                ],
                'banner_icon' => [
                    'type' => 'image',
                    'label' => 'Banner: Icon',
                ],
            ],
            'two_column_50_50' => [
                'body_2' => [
                    'type' => 'textarea',
                    'label' => 'Right column content',
                ],
                'image' => [
                    'type' => 'image',
                    'label' => 'Thumbnail',
                ],
            ],

        ],

        /*
        'news' => [
            'template_name' => [
                // config ...
            ]
        ]
        */
    ]

];

<?php

return [

    // All the sections for the settings page
    'sections' => [
        'app' => [
            'title' => 'General Settings',
            'icon' => 'fa fa-cog', // (optional)
            'inputs' => [
                [
                    'name' => 'site_name', // unique key for setting
                    'type' => 'text', // type of input can be text, number, textarea, select, boolean, checkbox etc.
                    'label' => 'Website Name', // label for input
                    // Optional settings:
                    //'placeholder' => 'Demo', // placeholder for input
                    'class' => 'form-control', // override global input_class
                    //'style' => '', // any inline styles
                    'rules' => 'required', // validation rules for this input
                    'value' => 'Demo', // any default value
                    'hint' => 'You can set the website name here'
                ],
                [
                    'name' => 'currency_symbol',
                    'type' => 'text',
                    'label' => 'Currency Symbol',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => '&pound;',
                    'hint' => 'Currency displayed in prices'
                ],
                [
                    'name' => 'currency',
                    'type' => 'text',
                    'label' => 'Currency',
                    'placeholder' => 'For feed exports e.g GBP / EUR',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => 'GBP',
                    'hint' => 'For feed exports e.g GBP / EUR'
                ],
                [
                    'name' => 'search_engine_visible',
                    'type' => 'select',
                    'label' => 'Enable Search engine to crawl',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => '0',
                    'hint' => 'You can enable Search engine to crawl',
                    'options' => [
                        '0' => 'No',
                        '1' => 'Yes'
                    ]
                ],
                [
                    'name' => 'primary_colour',
                    'type' => 'text',
                    'label' => 'Primary Hex Colour',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => '#',
                    'hint'  => 'Note - this is used for automated Emails, Colours and such like'
                ],
                [
                    'name' => 'footer_anchor_text',
                    'type' => 'text',
                    'label' => 'Footer Anchor Text',
                    'placeholder' => 'Website Design',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => 'Website Design',
                    'hint' => 'The link text displayed in the footer'
                ],
                [
                    'name' => 'google_map_api',
                    'type' => 'text',
                    'label' => 'Google Map API Key',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '',
                    'hint' => 'You can enable google map here',
                ],
                [
                    'name' => 'recaptcha_enabled',
                    'type' => 'select',
                    'label' => 'Enabled Recaptcha',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => '0',
                    'hint' => 'You can enable recaptcha here',
                    'options' => [
                        '0' => 'No',
                        '1' => 'Yes'
                    ]
                ],
                [
                    'name' => 'recaptcha_public_key',
                    'type' => 'text',
                    'label' => 'Recaptcha Public Key',
                    'placeholder' => 'Enter key',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '',
                    'hint' => 'Get this key in google recaptcha'
                ],
                [
                    'name' => 'recaptcha_private_key',
                    'type' => 'text',
                    'label' => 'Recaptcha Private Key',
                    'placeholder' => 'Enter key',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '',
                    'hint' => 'Get this key in google recaptcha'
                ],
                [
                    'name' => 'footer_scripts',
                    'type' => 'textarea',
                    'label' => 'Footer Scripts',
                    'placeholder' => 'eg.<script>...</script>',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '',
                    'hint' => 'Script for Google Analytics, etc..'
                ],
                [
                    'name' => 'maintenance_mode',
                    'type' => 'boolean',
                    'label' => 'Maintenance Mode?',
                    'value' => false,
                    'class' => 'w-auto',
                    'options' => [
                        '1' => 'Yes',
                        '0' => 'No',
                    ],
                    'hint' => 'Whether the site is down for maintenance. (Experimental)'
                ]
            ]
        ],
        'site_setup' => [
            'title' => 'Site Setup',
            'descriptions' => 'General Site Setup - Optional Modules to Show and setting site to sale or rent',

            'inputs' => [
                [
                    'name'    => 'sale_rent',
                    'type'    => 'select',
                    'label'   => 'Sale or Rent?',
                    'hint'    => 'Is the agent selling or renting, or both?',
                    'options' => [
                        'sale'        => 'Sale',
                        'rent'        => 'Rent',
                        'sale_rent'   => 'Sale & Rent'
                    ]
                ],
                [
                    'type' => 'checkbox',
                    'label' => 'Blog / News',
                    'name' => 'show_blog',
                    'value' => '1',
                ],
                [
                    'type' => 'checkbox',
                    'label' => 'Subscribers',
                    'name' => 'show_subscribers',
                    'value' => '1'
                ],
                [
                    'type' => 'checkbox',
                    'label' => 'Testimonials',
                    'name' => 'show_testimonials',
                    'value' => '1'
                ],
                [
                    'name'  => 'store_s3',
                    'type'  => 'select',
                    'label' => 'Store Images on an Amazon S3 Bucket',
                    'value' => 'false',
                    'hint' => 'If yes, use .env file to setup',
                    'options' => [
                        'true'  => 'Yes',
                        'false' => 'No'
                    ]
                ],
                [
                    'label'         => 'Property Reference Prefix',
                    'name'          => 'ref_prefix',
                    'type'          => 'text',
                    'placeholder'   => 'Property Reference Prefix',
                    'rules'         => 'required',
                    'value'         => 'PW-',
                    'hint'          => 'This is the prefix for a property ref i.e PW-55'
                ]
            ]
        ],
        'propertybase' => [
            'title' => 'Propertybase Settings',
            'descriptions' => 'If this is a Propertybase Enabled site enter details here',
            'icon' => 'fa fa-building',
            'inputs' => [
                [
                    'name'  => 'propertybase',
                    'type'  => 'select',
                    'label' => 'Is this site a Propertybase Site?',
                    'value' => false,
                    'hint'  => 'If yes, please provide org_id & token',
                    'options' => [
                        true  => 'Yes',
                        false => 'No'
                    ]
                ],
                [
                    'name' => 'propertybase_org',
                    'type' => 'text',
                    'label' => 'Propertybase Org ID',
                    'hint' => 'The Propertybase Org ID',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '',
                ],
                [
                    'name' => 'propertybase_token',
                    'type' => 'text',
                    'label' => 'Propertybase Token',
                    'hint' => 'The Propertybase Token',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '',
                ]
            ]
        ],
        'templates' => [
            'title' => 'Template Settings',
            'descriptions' => 'Chosen template for the three templated pages.',
            'icon' => 'fa fa-paint-brush',

            'inputs' => [
                [
                    'name'          => 'is_demo',
                    'type'          => 'boolean',
                    'label'         => 'Is this a Demo site?',
                    'rules'         => 'required',
                    'value'         => true,
                    'hint'          => 'If set to Yes, ALL Demos are available to view, Otherwise we will show the selected options below',
                    'true_value'    => 'Yes',
                    'false_value'   => 'No'
                ],
                [
                    'name'  => 'chosen_template',
                    'type'  => 'select',
                    'label' => 'Chosen Template',
                    'class' => 'form-control',
                    'value' => false,
                    'options' =>
                        [
                            '1' => 'Template 1',
                            '2' => 'Template 2',
                            '3' => 'Template 3'
                        ]
                ],
                [
                    'name' => 'template_home',
                    'type' => 'select',
                    'label' => 'Home Page Template',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => '1',
                    'hint' => 'You can set the home page template here',
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3'
                    ]
                ],
                [
                    'name' => 'template_search',
                    'type' => 'select',
                    'label' => 'Search Page Template',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => '1',
                    'hint' => 'You can set the search page template here',
                    'options' => [
                        '1' => '1',
                        '2' => '2',
                        '3' => '3'
                    ]
                ],
                [
                    'name' => 'template_details',
                    'type' => 'select',
                    'label' => 'Details Page Template',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => '1',
                    'hint' => 'You can set the details page template here',
                    'options' => [
                        '1' => '1',
                        '2' => '2'
                    ]
                ]
            ]
        ],
        'language_settings' => [
            'title'         => 'Language Settings',
            'descriptions'  => 'Any chosen languages here',
            'icon'          => 'fa fa-flag',
            'inputs'    => [
                [
                    'name' => 'translations',
                    'type' => 'boolean',
                    'label' => 'Enable Translations',
                    'hint' => 'If enabled configure languages under admin/settings/languages',
                    'value' => false,
                    'options' => [
                        '1' => 'Yes',
                        '0' => 'No',
                    ],
                ]
            ]
        ],
        'property_settings' => [
            'title' => 'Property View Settings',
            'descriptions' => 'Configs for property',
            'icon' => 'fa fa-house',
            'inputs' => [
                [
                    'name' => 'price_format',
                    'type' => 'select',
                    'label' => 'Default Price Format',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '0',
                    'options' => [
                        '0' => 'Comma',
                        '1' => 'Fullstop'
                    ]
                ],
                [
                    'name' => 'area_unit',
                    'type' => 'text',
                    'label' => 'Default Area Unit',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => 'sq m',
                ],
                [
                    'name' => 'area_unit_land',
                    'type' => 'text',
                    'label' => 'Land Area Unit',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => 'sq m',
                ],
                [
                    'name' => 'pdf_view',
                    'type' => 'select',
                    'label' => 'PDF View',
                    'class' => 'form-control',
                    'value' => '0',
                    'hint' => 'Allow PDF View',
                    'options' => [
                        '0' => 'No',
                        '1' => 'Yes'
                    ]
                ],
            ]
        ],
        'address' => [
            'title' => 'Address Settings',
            'descriptions' => 'Contact/location details',
            'icon' => 'fa fa-address-book',

            'inputs' => [
                [
                    'name' => 'overseas',
                    'type' => 'select',
                    'label' => 'Enabled Overseas',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => '0',
                    'hint' => 'If overseas enabled please provide a country',
                    'options' => [
                        '0' => 'No',
                        '1' => 'Yes'
                    ]
                ],
                [
                    'name' => 'overseas_country',
                    'type' => 'text',
                    'label' => 'If Overseas provide country',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '',
                    'hint' => 'For Overseas use only',
                ],
                [
                    'name' => 'footer_address',
                    'type' => 'textarea',
                    'label' => 'Footer Postal Address',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => 'Stainton Way, Acklam, TS5 ABC',
                    'hint' => 'The address as displayed in the footer'
                ],
                [
                    'name' => 'default_location',
                    'type' => 'text',
                    'label' => 'Target Location',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => 'required',
                    'value' => 'Stainton',
                    'hint' => 'The main/target location, for SEO and display purposes'
                ],
                [
                    'name' => 'contact_latitude',
                    'type' => 'text', // 'number' isn't letting it be a float in Firefox/Chrome...
                    'label' => 'Contact Us Map Latitude',
                    //'data_type' => 'float', // ...even with this specified
                    'class' => 'form-control',
                    'rules' => 'min:-90|max:90',
                    'value' => '54.5300017',
                    'hint' => 'Latitude of the Contact Us map'
                ],
                [
                    'name' => 'contact_longitude',
                    'type' => 'text', // 'number' isn't letting it be a float in Firefox/Chrome...
                    'label' => 'Contact Us Map Longitude',
                    //'data_type' => 'float', // ...even with this specified
                    'class' => 'form-control',
                    'rules' => 'min:-180|max:180',
                    'value' => '-1.2624551',
                    'hint' => 'Longitude of the Contact Us map'
                ],
                [
                    'name' => 'default_latitude',
                    'type' => 'text', // 'number' isn't letting it be a float in Firefox/Chrome...
                    'label' => 'Default Latitude',
                    //'data_type' => 'float', // ...even with this specified
                    'class' => 'form-control',
                    'rules' => 'min:-90|max:90',
                    'value' => '54.5300017',
                    'hint' => 'Starting latitude of property maps'
                ],
                [
                    'name' => 'default_longitude',
                    'type' => 'text', // 'number' isn't letting it be a float in Firefox/Chrome...
                    'label' => 'Default Longitude',
                    //'data_type' => 'float', // ...even with this specified
                    'class' => 'form-control',
                    'rules' => 'min:-180|max:180',
                    'value' => '-1.2624551',
                    'hint' => 'Starting longitude of property maps'
                ],
                [
                    'name' => 'featured_location1',
                    'type' => 'text',
                    'label' => 'Featured Location 1',
                    'class' => 'form-control',
                    'value' => 'Hartlepool',
                    'hint' => 'The first location to appear in the Featured Locations section'
                ],
                [
                    'name' => 'featured_location2',
                    'type' => 'text',
                    'label' => 'Featured Location 2',
                    'class' => 'form-control',
                    'value' => 'Durham',
                    'hint' => 'The second location to appear in the Featured Locations section'
                ],
                [
                    'name' => 'featured_location3',
                    'type' => 'text',
                    'label' => 'Featured Location 3',
                    'class' => 'form-control',
                    'value' => 'Teesside',
                    'hint' => 'The third location to appear in the Featured Locations section'
                ]
            ]
        ],

        'contact_us' => [
            'title' => 'Contact Us 1 Settings',
            'descriptions' => 'Configs for Contact Us',
            'icon' => '',
            'inputs' => [
                [
                    'name' => 'contact_heading_1',
                    'type' => 'text',
                    'label' => 'Contact Heading',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '',
                    'hint' => ''
                ],
                [
                    'name' => 'contact_address',
                    'type' => 'textarea',
                    'label' => 'Postal Address',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '<strong>Stainton Properties</strong><br>Stainton Way, Acklam<br>TS5 ABC</p>',
                    'hint' => 'The address as displayed on the Contact Us page'
                ],
                [
                    'name' => 'telephone',
                    'type' => 'text',
                    'label' => 'Telephone Number',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '+44 (0) 1642 123456',
                ],
                [
                    'name' => 'email_contact_1',
                    'type' => 'email',
                    'label' => 'Email Address',
                    'hint' => 'Contact email address displayed on website',
                    'placeholder' => 'info@staintonproperties.com',
                    'rules' => 'required|email',
                ],
            ]
        ],

        'contact_us_2' => [
            'title' => 'Contact Us 2 Settings',
            'descriptions' => 'Configs for Contact Us',
            'icon' => '',
            'inputs' => [
                [
                    'name' => 'contact_heading_2',
                    'type' => 'text',
                    'label' => 'Contact Heading',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '',
                    'hint' => ''
                ],
                [
                    'name' => 'contact_address_2',
                    'type' => 'textarea',
                    'label' => 'Postal Address',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '<strong>Stainton Properties</strong><br>Stainton Way, Acklam<br>TS5 ABC</p>',
                    'hint' => 'The address as displayed on the Contact Us page'
                ],
                [
                    'name' => 'telephone_2',
                    'type' => 'text',
                    'label' => 'Telephone Number',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '+44 (0) 1642 123456',
                ],
                [
                    'name' => 'email_contact_2',
                    'type' => 'email',
                    'label' => 'Email Address',
                    'hint' => 'Contact email address displayed on website',
                    'placeholder' => 'info@staintonproperties.com',
                    'rules' => 'required|email',
                ],
            ]
        ],

        'toll_free' => [
            'title' => 'Toll Free Settings',
            'descriptions' => 'Configs for Contact Us',
            'icon' => '',
            'inputs' => [
                [
                    'name' => 'toll_free_1',
                    'type' => 'text',
                    'label' => 'UK',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '+44 (0) 1642 123456',
                ],
                [
                    'name' => 'toll_free_2',
                    'type' => 'text',
                    'label' => 'Ireland',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '+44 (0) 1642 123456',
                ],
                [
                    'name' => 'toll_free_3',
                    'type' => 'text',
                    'label' => 'US/Can',
                    'placeholder' => '',
                    'class' => 'form-control',
                    'rules' => '',
                    'value' => '+44 (0) 1642 123456',
                ],

            ]
        ],

        'social_media' => [
            'title' => 'Social Media Settings',
            'descriptions' => 'Configs for social media',
            'icon' => '',
            'inputs' => [
                [
                    'name' => 'facebook_url',
                    'type' => 'text',
                    'label' => 'Facebook Page Address',
                    'placeholder' => 'Facebook Website Address',
                    'class' => 'form-control',
                    'value' => '',
                ],
                [
                    'name' => 'twitter_url',
                    'type' => 'text',
                    'label' => 'Twitter Page Address',
                    'placeholder' => 'Twitter Page Address',
                    'class' => 'form-control',
                    'value' => '',
                ],
                [
                    'name' => 'whatsapp_url',
                    'type' => 'text',
                    'label' => 'Whatsapp Number',
                    'placeholder' => 'Whatsapp Number',
                    'class' => 'form-control',
                    'value' => '',
                ],
                [
                    'name' => 'instagram_url',
                    'type' => 'text',
                    'label' => 'Instagram Web Address',
                    'placeholder' => 'Instagram Website Address',
                    'class' => 'form-control',
                    'value' => '',
                ],
                [
                    'name' => 'linkedin_url',
                    'type' => 'text',
                    'label' => 'Linkedin Page Address',
                    'placeholder' => 'Linkedin Website Address',
                    'class' => 'form-control',
                    'value' => '',
                ],
                [
                    'name' => 'youtube_url',
                    'type' => 'text',
                    'label' => 'Youtube Page Address',
                    'placeholder' => 'Youtube Website Address',
                    'class' => 'form-control',
                    'value' => '',
                ],
                [
                    'name' => 'pinterest_url',
                    'type' => 'text',
                    'label' => 'Pinterest Page Address',
                    'placeholder' => 'Pinterest Website Address',
                    'class' => 'form-control',
                    'value' => '',
                ],
                [
                    'name' => 'tiktok_url',
                    'type' => 'text',
                    'label' => 'TikTok Address',
                    'placeholder' => 'TikTok Address',
                    'class' => 'form-control',
                    'value' => '',
                ]
            ]
        ],
        'addons' => [
            'title' => 'Addons',
            'descriptions' => 'Bespoke Addons',
            'icon' => 'fa fa-plus',
            'inputs' => [
                [
                    'name'  => 'members_area',
                    'type'  => 'boolean',
                    'label' => 'Members Area',
                    'hint'  => 'An area for users to save their searches and shortlists',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0'
                ],
                [
                    'name'  => 'team_page',
                    'type'  => 'boolean',
                    'label' => 'Team Page',
                    'hint'  => 'Include a team page in the site',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0'
                ],
                [
                    'name'  => 'new_developments',
                    'type'  => 'boolean',
                    'label' => 'New Developments',
                    'hint'  => 'Enable New Developments - Allows Units & Differing Property Types',
                    'value' => false,
                    'options' => [
                        true  => 'Yes',
                        false => 'No'
                    ]
                ],
                [
                    'name'  => 'bespoke_pages',
                    'type'  => 'boolean',
                    'label' => 'Bespoke / Custom Pages',
                    'hint'  => 'Custom Pages / Pillar Pages',
                    'value' => false,
                    'true_value'    => '1',
                    'false_value'   => '0'
                ],
                [
                    'name' => 'market_valuation',
                    'type' => 'boolean',
                    'label' => 'Market Valuation Module',
                    'hint' => 'Market Valuation module - Send valuations to clients and allow them to view',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0',
                ],
                [
                    'name' => 'footer_blocks',
                    'type' => 'boolean',
                    'label' => 'Footer Blocks & Links',
                    'hint' => 'Footer Blocks - Used for SEO / Marketing',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0',
                ],
                [
                    'name' => 'rightmove',
                    'type' => 'boolean',
                    'label' => 'Rightmove API',
                    'hint'  => 'Produce Rightmove Property Feeds for this site',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0'
                ],
                [
                    'name' => 'rightmove_branch_id',
                    'type' => 'text',
                    'label' => 'Rightmove Branch ID',
                    'hint' => 'Rightmove provided Branch ID - To post the properties to',
                    'value' => 0
                ],
                [
                    'name' => 'zoopla',
                    'type' => 'boolean',
                    'label' => 'Zoopla API',
                    'hint'  => 'Produce Zoopla Property Feeds for this site',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0'
                ],
                [
                    'name' => 'zoopla_branch_reference',
                    'type' => 'text',
                    'label' => 'Zoopla Branch Reference',
                    'hint'  => 'Zoopla provided Branch Reference - To post the properties to',
                    'value' => 0
                ],
                [
                    'name'  =>  'kyero_feed',
                    'type' => 'boolean',
                    'label' => 'Kyero Feed Output',
                    'hint'  => 'Produce a feed for Kyero',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0'
                ],
                [
                    'name' => 'seo_search',
                    'type' => 'boolean',
                    'label' => 'SEO Search',
                    'hint' => 'SEO Content for specific searches',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0',
                ],
                [
                    'name'  => 'social_sharing',
                    'type'  => 'boolean',
                    'label' => 'Enable Social Media Sharing',
                    'hint'  => 'Enables Facebook Dialog, Allowing Properties to be shared',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0'
                ],
                [
                    'name' => 'branches_option',
                    'type' => 'boolean',
                    'label' => 'Allow multiple branches for this agent',
                    'hint' => 'Allows multiple branches to be assigned to the global agent',
                    'value' => false,
                    'true_value' => '1',
                    'false_value' => '0'
                ]
            ]
        ],
        'email' => [
            'title' => 'Email Settings',
            'descriptions' => 'How app email will be sent.',
            'icon' => 'fa fa-envelope',

            'inputs' => [
                [
                    'name' => 'email',
                    'type' => 'email',
                    'label' => 'Primary Contact Email Address',
                    'hint' => 'Contact email address displayed on website',
                    'placeholder' => 'info@staintonproperties.com',
                    'rules' => 'required|email',
                ],
                [
                    'name' => 'secondary_email',
                    'type' => 'email',
                    'label' => 'Additional Contact Email Address',
                    'placeholder' => 'hello@terezaestates.com',
                    'rules' => 'email',
                ],
                [
                    'name' => 'technical_email',
                    'type' => 'email',
                    'label' => 'Technical Contact Email Address',
                    'hint' => 'The contact email address for technical matters',
                    'placeholder' => 'hello@terezaestates.com',
                    'rules' => 'required|email',
                ],
                [
                    'name' => 'from_email',
                    'type' => 'email',
                    'label' => 'From: Email',
                    'hint' => 'The From: address for emails',
                    'placeholder' => 'info@staintonproperties.com',
                    'rules' => 'required|email',
                ]
            ]
        ]
    ],

    // Setting page url, will be used for get and post request
    'url' => '/admin/settings',

    // Any middleware you want to run on above route
    'middleware' => ['super'],

    // View settings
    'setting_page_view' => 'backend/settings/index',
    'flash_partial' => 'app_settings::_flash',

    // Setting section class setting
    'section_class' => 'x_panel',
    'section_heading_class' => 'x_title',
    'section_body_class' => 'xpw-fields',

    // Input wrapper and group class setting
    'input_wrapper_class' => 'form-group',
    'input_class' => 'form-control',
    'input_error_class' => 'has-error',
    'input_invalid_class' => 'is-invalid',
    'input_hint_class' => 'form-text text-muted',
    'input_error_feedback_class' => 'text-danger',

    // Submit button
    'submit_btn_text' => 'Save Settings',
    'submit_success_message' => 'Settings have been saved.',

    // Remove any setting which declaration removed later from sections
    'remove_abandoned_settings' => false,

    // Controller to show and handle save setting
    'controller' => '\QCod\AppSettings\Controllers\AppSettingController',

    // Eloquent model for setting
    'model' => '\QCod\AppSettings\Setting\Setting',

    /*------------------------------------
    * Site Config here...
    ------------------------------------*/
    'website' =>[
        'name' => '',
    ],

    /*------------------------------------
    * Property Config here...
    ------------------------------------*/
    'property' =>[
        'currency_symbol' => '£',
    ]


];

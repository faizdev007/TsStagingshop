<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => 'The <span class="field-name">:attribute</span> must be accepted.',
    'active_url'           => 'The <span class="field-name">:attribute</span> is not a valid URL.',
    'after'                => 'The <span class="field-name">:attribute</span> must be a date after :date.',
    'after_or_equal'       => 'The <span class="field-name">:attribute</span> must be a date after or equal to :date.',
    'alpha'                => 'The <span class="field-name">:attribute</span> may only contain letters.',
    'alpha_dash'           => 'The <span class="field-name">:attribute</span> may only contain letters, numbers, dashes and underscores.',
    'alpha_num'            => 'The <span class="field-name">:attribute</span> may only contain letters and numbers.',
    'array'                => 'The <span class="field-name">:attribute</span> must be an array.',
    'before'               => 'The <span class="field-name">:attribute</span> must be a date before :date.',
    'before_or_equal'      => 'The <span class="field-name">:attribute</span> must be a date before or equal to :date.',
    'between'              => [
        'numeric' => 'The <span class="field-name">:attribute</span> must be between :min and :max.',
        'file'    => 'The <span class="field-name">:attribute</span> must be between :min and :max kilobytes.',
        'string'  => 'The <span class="field-name">:attribute</span> must be between :min and :max characters.',
        'array'   => 'The <span class="field-name">:attribute</span> must have between :min and :max items.',
    ],
    'boolean'              => 'The <span class="field-name">:attribute</span> field must be true or false.',
    'confirmed'            => 'The <span class="field-name">:attribute</span> confirmation does not match.',
    'date'                 => 'The <span class="field-name">:attribute</span> is not a valid date.',
    'date_format'          => 'The <span class="field-name">:attribute</span> does not match the format :format.',
    'different'            => 'The <span class="field-name">:attribute</span> and :other must be different.',
    'digits'               => 'The <span class="field-name">:attribute</span> must be :digits digits.',
    'digits_between'       => 'The <span class="field-name">:attribute</span> must be between :min and :max digits.',
    'dimensions'           => 'The <span class="field-name">:attribute</span> has invalid image dimensions.',
    'distinct'             => 'The <span class="field-name">:attribute</span> field has a duplicate value.',
    'email'                => 'The <span class="field-name">:attribute</span> must be a valid email address.',
    'exists'               => 'The selected <span class="field-name">:attribute</span> is invalid.',
    'file'                 => 'The <span class="field-name">:attribute</span> must be a file.',
    'filled'               => 'The <span class="field-name">:attribute</span> field must have a value.',
    'gt'                   => [
        'numeric' => 'The <span class="field-name">:attribute</span> must be greater than :value.',
        'file'    => 'The <span class="field-name">:attribute</span> must be greater than :value kilobytes.',
        'string'  => 'The <span class="field-name">:attribute</span> must be greater than :value characters.',
        'array'   => 'The <span class="field-name">:attribute</span> must have more than :value items.',
    ],
    'gte'                  => [
        'numeric' => 'The <span class="field-name">:attribute</span> must be greater than or equal :value.',
        'file'    => 'The <span class="field-name">:attribute</span> must be greater than or equal :value kilobytes.',
        'string'  => 'The <span class="field-name">:attribute</span> must be greater than or equal :value characters.',
        'array'   => 'The <span class="field-name">:attribute</span> must have :value items or more.',
    ],
    'image'                => 'The <span class="field-name">:attribute</span> must be an image.',
    'in'                   => 'The selected <span class="field-name">:attribute</span> is invalid.',
    'in_array'             => 'The <span class="field-name">:attribute</span> field does not exist in :other.',
    'integer'              => 'The <span class="field-name">:attribute</span> must be an integer.',
    'ip'                   => 'The <span class="field-name">:attribute</span> must be a valid IP address.',
    'ipv4'                 => 'The <span class="field-name">:attribute</span> must be a valid IPv4 address.',
    'ipv6'                 => 'The <span class="field-name">:attribute</span> must be a valid IPv6 address.',
    'json'                 => 'The <span class="field-name">:attribute</span> must be a valid JSON string.',
    'lt'                   => [
        'numeric' => 'The <span class="field-name">:attribute</span> must be less than :value.',
        'file'    => 'The <span class="field-name">:attribute</span> must be less than :value kilobytes.',
        'string'  => 'The <span class="field-name">:attribute</span> must be less than :value characters.',
        'array'   => 'The <span class="field-name">:attribute</span> must have less than :value items.',
    ],
    'lte'                  => [
        'numeric' => 'The <span class="field-name">:attribute</span> must be less than or equal :value.',
        'file'    => 'The <span class="field-name">:attribute</span> must be less than or equal :value kilobytes.',
        'string'  => 'The <span class="field-name">:attribute</span> must be less than or equal :value characters.',
        'array'   => 'The <span class="field-name">:attribute</span> must not have more than :value items.',
    ],
    'max'                  => [
        'numeric' => 'The <span class="field-name">:attribute</span> may not be greater than :max.',
        'file'    => 'The <span class="field-name">:attribute</span> may not be greater than :max kilobytes.',
        'string'  => 'The <span class="field-name">:attribute</span> may not be greater than :max characters.',
        'array'   => 'The <span class="field-name">:attribute</span> may not have more than :max items.',
    ],
    'mimes'                => 'The <span class="field-name">:attribute</span> must be a file of type: :values.',
    'mimetypes'            => 'The <span class="field-name">:attribute</span> must be a file of type: :values.',
    'min'                  => [
        'numeric' => 'The <span class="field-name">:attribute</span> must be at least :min.',
        'file'    => 'The <span class="field-name">:attribute</span> must be at least :min kilobytes.',
        'string'  => 'The <span class="field-name">:attribute</span> must be at least :min characters.',
        'array'   => 'The <span class="field-name">:attribute</span> must have at least :min items.',
    ],
    'not_in'               => 'The selected <span class="field-name">:attribute</span> is invalid.',
    'not_regex'            => 'The <span class="field-name">:attribute</span> format is invalid.',
    'numeric'              => 'The <span class="field-name">:attribute</span> must be a number.',
    'present'              => 'The <span class="field-name">:attribute</span> field must be present.',
    'regex'                => 'The <span class="field-name">:attribute</span> format is invalid.',
    'required'             => 'The <span class="field-name">:attribute</span> field is required.',
    'required_if'          => 'The <span class="field-name">:attribute</span> field is required when :other is :value.',
    'required_unless'      => 'The <span class="field-name">:attribute</span> field is required unless :other is in :values.',
    'required_with'        => 'The <span class="field-name">:attribute</span> field is required when :values is present.',
    'required_with_all'    => 'The <span class="field-name">:attribute</span> field is required when :values is present.',
    'required_without'     => 'The <span class="field-name">:attribute</span> field is required when :values is not present.',
    'required_without_all' => 'The <span class="field-name">:attribute</span> field is required when none of :values are present.',
    'same'                 => 'The <span class="field-name">:attribute</span> and :other must match.',
    'size'                 => [
        'numeric' => 'The <span class="field-name">:attribute</span> must be :size.',
        'file'    => 'The <span class="field-name">:attribute</span> must be :size kilobytes.',
        'string'  => 'The <span class="field-name">:attribute</span> must be :size characters.',
        'array'   => 'The <span class="field-name">:attribute</span> must contain :size items.',
    ],
    'string'               => 'The <span class="field-name">:attribute</span> must be a string.',
    'timezone'             => 'The <span class="field-name">:attribute</span> must be a valid zone.',
    'unique'               => 'The <span class="field-name">:attribute</span> has already been taken.',
    'uploaded'             => 'The <span class="field-name">:attribute</span> failed to upload.',
    'url'                  => 'The <span class="field-name">:attribute</span> format is invalid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'is_rental' => 'Field Type',
        'user_id' => 'Agent',
        'property_type_id' => 'Property Type',
        'beds' => 'Bedrooms',
        'baths' => 'Bathrooms',
        'price' => 'Price',
        'add_info' => 'Additional Info',
        'description' => 'Description',
        'city' => 'City',
        'internal_area' => 'Area',
        'terrace_area' => 'Terrace',
        'name' => 'Name',
        'youtube_id' => 'Youtube Link',
    ],

];

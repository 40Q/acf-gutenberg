<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

// Instantiate Global Field
// We should bring it from another place
$designFields = new FieldsBuilder('global_fields');

$designFields
    ->addTab('design')
    ->addText('custom_classes')
    ->addText('custom_id');

$designFieldsBuild = $designFields->build();
$designFieldsArray = $designFieldsBuild['fields'];

// Instantiate Global Field
// We should bring it from another place
$animationFields = new FieldsBuilder('global_fields');

$animationFields
    ->addTab('animation')
    ->addTrueFalse('animate', [
        'default_value' => 1,
    ])
    ->addSelect('animation_type', [
        'choices'=> [
            ['fade' => 'fade'],
            ['fade-up' => 'fade-up'],
            ['fade-down' => 'fade-down'],
            ['fade-left' => 'fade-left'],
            ['fade-right' => 'fade-right'],
            ['fade-up-right' => 'fade-up-right'],
            ['fade-up-left' => 'fade-up-left'],
            ['fade-down-right' => 'fade-down-right'],
            ['fade-down-left' => 'fade-down-left'],
            ['flip-up' => 'flip-up'],
            ['flip-down' => 'flip-down'],
            ['flip-left' => 'flip-left'],
            ['flip-right' => 'flip-right'],
            ['slide-up' => 'slide-up'],
            ['slide-down' => 'slide-down'],
            ['slide-left' => 'slide-left'],
            ['slide-right' => 'slide-right'],
            ['zoom-in' => 'zoom-in'],
            ['zoom-in-up' => 'zoom-in-up'],
            ['zoom-in-down' => 'zoom-in-down'],
            ['zoom-in-left' => 'zoom-in-left'],
            ['zoom-in-right' => 'zoom-in-right'],
            ['zoom-out' => 'zoom-out'],
            ['zoom-out-up' => 'zoom-out-up'],
            ['zoom-out-down' => 'zoom-out-down'],
            ['zoom-out-left' => 'zoom-out-left'],
            ['zoom-out-right' => 'zoom-out-right'],
        ],
        'default_value' => 'fade'
    ])
    ->conditional('animate', '==', 1);

$animationFieldsBuild = $animationFields->build();
$animationFieldsArray = $animationFieldsBuild['fields'];

return [

    /*
    |--------------------------------------------------------------------------
    | Default Field Type Settings
    |--------------------------------------------------------------------------
    |
    | Here you can set default field group and field type configuration that
    | is then merged with your field groups when they are composed.
    |
    | This allows you to avoid the repetitive process of setting common field
    | configuration such as `ui` on every `trueFalse` field or your
    | preferred `instruction_placement` on every `fieldGroup`.
    |
    */

    'defaults' => [
        'trueFalse' => ['ui' => 1],
        'select'    => ['ui' => 1],
        'textarea'  => ['new_lines' => 'br', 'rows' => 3],
        'image'     => ['return_format' => 'array', 'preview_size' => 'medium'],
    ],
    'globalfields' => [
        'design_tab' => [
            'key'       => $designFieldsBuild['key'],
            'fields'    => $designFieldsArray,
        ],
        'animation_tab' => [
            'key'       => $animationFieldsBuild['key'],
            'fields'    => $animationFieldsArray,
        ]
    ],

];

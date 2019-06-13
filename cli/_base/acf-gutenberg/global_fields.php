<?php
use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;



add_filter('acfgb_global_fields', function ($global_fields) {
    /**
     * Overwritten default values for global fields
     */
    $global_fields['full_height'] = true;

    return $global_fields;
});


add_filter('acfgb_design_global_fields', function ($design_global_fields) {
    /**
     * Add custom global fields fot design tab
     */

/*
    $design_global_fields = new FieldsBuilder('design-global-fields');
    $design_global_fields
        ->addTrueFalse('vertical_bottom',[
            'default_value' => 0,
            'ui' => 1
        ]);
*/

    return $design_global_fields;
});


add_filter('acfgb_content_global_fields', function ($content_global_fields) {
    /**
     * Add custom global fields fot content tab
     */

/*
    $content_global_fields = new FieldsBuilder('content-global-fields');
    $content_global_fields
        ->addTrueFalse('icon',[
            'default_value' => 0,
            'ui' => 1
        ]);
*/

    return $content_global_fields;
});



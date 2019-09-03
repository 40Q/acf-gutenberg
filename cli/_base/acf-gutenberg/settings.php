<?php

add_filter('acfgb_theme_colors', function ($colors) {
    // Replace the default colors
    $colors = [
        'primary' => 'Primary',
        'gray-lighter' => 'Gray lighter',
        'gray-light' => 'Gray Light',
        'orange' => 'Orange',
    ];

    return $colors;
});

add_filter('acfgb_button_classes', function ($classes) {

    // Replace the default colors
    $classes = [
        'btn-primary'=>'Primary',
        'btn-secondary'=>'Secondary',
        'gray-light' => 'Gray Light',
        'orange' => 'Orange',
    ];

    return $classes;
});



add_filter('acfgb_views', function ($views) {
    /**
     * Add custom views path
     */
    //$views[] = get_template_directory() . '/views';
    //$views[] = get_template_directory() . '/acf-gutenberg/blocks/acfgb-sample-block';
    //$views[] = get_template_directory() . '/custom/view/path';
    return $views;
});

add_filter('acfgb_components', function ($components) {
    /**
     * Add custom components
     */
    $components['button'] = 'components.button';
    return $components;
});

add_filter('acfgb_block_paths', function ($block_paths) {
    /**
     * Add custom block_paths path
     */
    //$block_paths[] = get_template_directory() . '/custom/blocks/path';
    return $block_paths;
});

add_filter('acfgb_blocks_category', function ($blocks_category) {
    /**
     * Add custom blocks_category path
     */
    $blocks_category = [
        'slug' => 'my-project-blocks',
        'title' => __( 'My Project Blocks', 'acf-gutenberg' ),
        'icon'  => 'wordpress',
    ];

    return $blocks_category;
});


add_filter('acfgb_global_fields', function ($global_fields) {
    /**
     * Overwritten default values for global fields
     */
    $global_fields['full_height'] = true;

    return $global_fields;
});

add_filter('acfgb_field_options', function ($field_options) {
    /**
     * Add or overwritten field options
     */
    $field_options['text_align'] = [
        'text-left' => 'Left',
        'text-center' => 'Center',
        'text-right' => 'Right',
        'text-justify' => 'Justify',
    ];

    return $field_options;
});

add_filter('acfgb_blocks_disabled', function ($blocks_disabled) {
    /**
     * Add blocks (slug) that will be disabled
     */
    $blocks_disabled = [
        'latest-news',
        //'block-slug',
    ];

    return $blocks_disabled;
});

add_filter('acfgb_compatibility_mode', function ($compatibility_mode) {
    /**
     * Change variable to true if you want active compatibility with old ACFG version
     */
    $compatibility_mode = false;

    return $compatibility_mode;
});


add_filter( 'acfgb_allowed_default_block', function () {
	return array(

		/* Common blocks */
		'core/paragraph',
//        'core/image',
//        'core/heading',
//        'core/gallery',
//        'core/list',
//        'core/quote',
//        'core/audio',
//        'core/cover',
//        'core/file',
//        'core/video',

		/* Formatting blocks */
//        'core/table',
//        'core/verse',
//        'core/code',
//        'core/freeform',
//        'core/html',
//        'core/preformatted',
//        'core/pullquote',

		/* Layout Elements blocks */
//        'core/button',
//        'core/text-columns',
//        'core/media-text',
//        'core/more',
//        'core/nextpage',
//        'core/separator',
//        'core/spacer',
//
		/* Widgets blocks */
//        'core/shortcode',
//        'core/archives',
//        'core/categories',
//        'core/latest-comments',
//        'core/latest-posts',
//        'core/calendar',
//        'core/rss',
//        'core/search',
//        'core/tag-cloud',
//
		/* Embeds blocks */
//        'core/embed',
//        'core-embed/twitter',
//        'core-embed/youtube',
//        'core-embed/facebook',
//        'core-embed/instagram',
//        'core-embed/wordpress',
//        'core-embed/soundcloud',
//        'core-embed/spotify',
//        'core-embed/flickr',
//        'core-embed/vimeo',
//        'core-embed/animoto',
//        'core-embed/cloudup',
//        'core-embed/collegehumor',
//        'core-embed/dailymotion',
//        'core-embed/funnyordie',
//        'core-embed/hulu',
//        'core-embed/imgur',
//        'core-embed/issuu',
//        'core-embed/kickstarter',
//        'core-embed/meetup-com',
//        'core-embed/photobucket',
//        'core-embed/polldaddy',
//        'core-embed/reddit',
//        'core-embed/reverbnation',
//        'core-embed/scribd',
//        'core-embed/slideshare',
//        'core-embed/smugmug',
//        'core-embed/speaker',
//        'core-embed/ted',
//        'core-embed/tumblr',
//        'core-embed/videopress',
//        'core-embed/wordpress-tv',
	);
});




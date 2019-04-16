<?php

use ACF_Gutenberg\Lib;
abstract class ACF_Blocks{

    static public function acf_init (){

        // check function exists
        if (function_exists('acf_register_block')) {
            collect(glob(ACFGB_PATH_RESOURCES . '/blocks/{,*/}{*}settings.php', GLOB_BRACE))->map(function ($field) {
                $block_params = require_once $field;
                if (is_array($block_params)) {
                    acf_register_block($block_params);
                }
            });
        }

        if (function_exists('acf_register_block')) {
            collect(glob(get_template_directory() . '/acf-gutenberg/blocks/{,*/}{*}settings.php', GLOB_BRACE))->map(function ($field) {
                $block_params = require_once $field;
                if (is_array($block_params)) {
                    acf_register_block($block_params);
                }
            });
        }

    }


    static public function acf_builder_init (){
        // Register Classes/Controller
        collect(glob(ACFGB_PATH_RESOURCES . '/classes/*.php'))->map(function ($field) {
            return require_once $field;
        });

        // Register any custom class
        collect(glob(ACFGB_PATH_RESOURCES . '/blocks/{,*/}{*}.class.php', GLOB_BRACE))->map(function ($field) {
            return require_once $field;
        });

        // Register Fields
        collect(glob(ACFGB_PATH_RESOURCES . '/blocks/{,*/}{*}fields.php', GLOB_BRACE))->map(function ($field) {
            return require_once $field;
        })->map(function ($fields) {
            if (function_exists('acf_add_local_field_group')) {
                foreach ($fields as $field) {
                    $block_content = $field->build();
                    \ACF_Gutenberg\Classes\Config::createDynamic(str_replace('group_', '', $block_content['key']), array_column($block_content['fields'], 'name'));
                    acf_add_local_field_group($block_content);
                }
            }
        });


        // echo '<pre>';
        // print_r(\ACF_Gutenberg\Classes\Config::threecolumns());
        // echo '</pre>';
        // die();

        // Register any custom class
        collect(glob(get_template_directory() . '/acf-gutenberg/blocks/{,*/}{*}.class.php', GLOB_BRACE))->map(function ($field) {
            return require_once $field;
        });

        // Register Fields
        collect(glob(get_template_directory() . '/acf-gutenberg/blocks/{,*/}{*}fields.php', GLOB_BRACE))->map(function ($field) {
            return require_once $field;
        })->map(function ($fields) {
            if (function_exists('acf_add_local_field_group')) {
                foreach ($fields as $field) {
                    $block_content = $field->build();
                    \ACF_Gutenberg\Classes\Config::createDynamic(str_replace('group_', '', $block_content['key']), array_column($block_content['fields'], 'name'));
                    acf_add_local_field_group($block_content);
                }
            }
        });
    }


    /**
     * Render Fields
     */
    static public function my_acf_block_render_callback($block)
    {
        // convert name ("acf/testimonial") into path friendly slug ("testimonial")
        $slug = str_replace('acf/', '', $block['name']);

        $array = call_user_func(['ACF_Gutenberg\Classes\Config', $slug]);

        if (is_array($array)) {
            $slug_to_class = Lib\convert_to_class_name($slug);
            if (class_exists('ACF_Gutenberg\\Blocks\\' . $slug_to_class)) {
                $class = 'ACF_Gutenberg\\Blocks\\' . $slug_to_class;
            } else {
                $class = 'ACF_Gutenberg\\Classes\\Block';
            }
            $block = new $class($array, $slug);
        }

        if (file_exists(ACFGB_PATH_RESOURCES . "/blocks/{$slug}/{$slug}.blade.php")) {
            Lib\render_plugin_view("{$slug}.{$slug}", ['block' => $block]);
        } elseif (file_exists(ACFGB_PATH_RESOURCES . "/blocks/{$slug}/index.blade.php")) {
            Lib\render_plugin_view("{$slug}.index", ['block' => $block]);
        } elseif (get_template_directory() . "/acf-gutenberg/blocks/{$slug}/{$slug}.blade.php") {
            Lib\render_theme_view("{$slug}.{$slug}", ['block' => $block]);
        }
    }




}
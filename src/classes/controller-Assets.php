<?php

abstract class Assets{

    static public function enqueue_block_editor_assets (){

        // Make paths variables so we don't write em twice ;)
        $block_path = '/assets/js/editor.blocks.js';
        $style_path = '/assets/css/blocks.editor.css';

        // Enqueue the bundled block JS file
        wp_enqueue_script(
            'jsforwp-blocks-js',
            ACFGB_URL_RESOURCES . $block_path,
            ['wp-i18n', 'wp-element', 'wp-blocks', 'wp-components'],
            filemtime(ACFGB_PATH_RESOURCES . $block_path)
        );

        // Enqueue optional editor only styles
        wp_enqueue_style(
            'jsforwp-blocks-editor-css',
            ACFGB_URL_RESOURCES . $style_path,
            ['wp-blocks'],
            filemtime(ACFGB_PATH_RESOURCES . $style_path)
        );

    }


    static public function enqueue_assets (){
        $style_path = '/assets/css/blocks.style.css';
        wp_enqueue_style(
            'jsforwp-blocks',
            ACFGB_URL_RESOURCES . $style_path,
            ['wp-blocks'],
            filemtime(ACFGB_PATH_RESOURCES . $style_path)
        );
    }


    static public function enqueue_frontend_assets (){
        // If in the backend, bail out.
        if (is_admin()) {
            return;
        }

        $block_path = '/assets/js/frontend.blocks.js';
        wp_enqueue_script(
            'jsforwp-blocks-frontend',
            ACFGB_URL_RESOURCES . $block_path,
            [],
            filemtime(ACFGB_PATH_RESOURCES . $block_path)
        );
    }


}
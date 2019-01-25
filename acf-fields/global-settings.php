<?php

use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Global Settings
 */
$fields['global_settings'] = new FieldsBuilder('global_settings');
$fields['global_settings']
    ->addTab('header', ['placement' => 'left'])
        ->addImage('logo', [
            'preview_size' => 'medium'
        ])
    ->addTab('footer', ['placement' => 'left'])
        ->addImage('footer_logo', [
            'preview_size' => 'medium'
        ])
    ->setLocation('options_page', '==', 'global-options');

return $fields;

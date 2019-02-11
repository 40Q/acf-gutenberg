<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Sample Block
 */
$fields['simple-text'] = new FieldsBuilder('simple-text');
$fields['simple-text']
    ->addText('text', [
        'default_value' => 'Sample Text'
    ])
    ->addTextarea('intro', [
        'rows' => 3,
        'default_value' => 'Sample Introduction'
    ])
    ->addText('btn_label', [
        'default_value' => 'Button Label'
    ])
    ->addText('btn_url', [
        'default_value' => 'Button URL'
    ])
    ->setLocation('block', '==', 'acf/simple-text');

return $fields;

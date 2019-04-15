<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Sample Block
 */
$fields['sample-block'] = new FieldsBuilder('sample-block');
$fields['sample-block']
    ->addText('text', [
        'default_value' => 'Sample Text'
    ])
    ->addTextarea('intro', [
        'rows' => 2,
        'default_value' => 'Sample Introduction'
    ])
    ->setLocation('block', '==', 'acf/sample-block');

return $fields;

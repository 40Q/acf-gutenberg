<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Sample Block
 */
$fields['sample-block'] = new FieldsBuilder('sample-block');
$fields['sample-block']
    ->addText('text')
    ->addTextarea('intro', [
        'rows' => 2
    ])
    ->setLocation('block', '==', 'acf/sample-block');

return $fields;

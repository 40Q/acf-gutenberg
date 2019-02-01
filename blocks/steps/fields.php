<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Steps
 */
$fields['steps'] = new FieldsBuilder('steps');
$fields['steps']
    ->addImage('image', [
        'preview_size' => 'large'
    ])
    ->addTrueFalse('featured')
    ->addText('heading')
    ->addText('tagline')
    ->addTextarea('intro', [
        'rows' => 2
    ])
    ->addTrueFalse('solid_card')
    ->setLocation('block', '==', 'acf/steps');

    return $fields;

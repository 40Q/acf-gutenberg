<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Slider
 */
$fields['slider'] = new FieldsBuilder('slider');
$fields['slider']
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
    ->setLocation('block', '==', 'acf/slider');

return $fields;

<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Steps
 */
$fields['threecolumns'] = new FieldsBuilder('threecolumns');
$fields['threecolumns']
    ->addRepeater('columns', [
        'layout' => 'block'
    ])
        ->addText('icon')
        ->addText('title')
        ->addWysiwyg('content')
    ->setLocation('block', '==', 'acf/threecolumns');

    return $fields;

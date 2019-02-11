<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Sample Block
 */
$fields['latest-news'] = new FieldsBuilder('latest-news');
$fields['latest-news']
    ->addText('title')
    ->setLocation('block', '==', 'acf/latest-news');

return $fields;

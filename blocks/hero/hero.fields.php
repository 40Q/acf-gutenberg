<?php
use StoutLogic\AcfBuilder\FieldsBuilder;

/**
 * Hero
 */
$fields['hero'] = new FieldsBuilder('hero');
$fields['hero']
    ->addImage('image', [
        'preview_size' => 'large'
    ])
    ->addText('heading')
    ->addTextarea('intro', [
        'rows' => 2
    ])
    ->addText('video_url')
    ->setLocation('block', '==', 'acf/hero');

return $fields;

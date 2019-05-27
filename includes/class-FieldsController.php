<?php

/**
 * Class Fields
 * @since      1.1.0
 *
 * @package    ACF_Gutenberg
 * @subpackage ACF_Gutenberg/includes
 *
 * This class is meant to be extended in order to separate the functionality of
 * a builder block from its presentation.
 *
 * @property-read $position
 * @property-read $title
 * @property-read $text
 */

namespace ACF_Gutenberg\Classes;
use StoutLogic\AcfBuilder\FieldsBuilder;

class FieldsController
{

    public $button_classes = [
        'btn-primary'=>'Primary',
        'btn-secondary'=>'Secondary'
    ];

    public function set_fields()
    {
        $fields['acf-block'] = new FieldsBuilder('acf-block');
        $fields['acf-block']
            ->addText('title')
            ->addText('intro')
            ->addText('text')
            ->setLocation('block', '==', 'acf/acf-block');
        return $fields;
    }

    public function set_global_fields($global_fields, $fields_config)
    {
        if (isset($global_fields) && is_array($fields_config)) {
            return array_merge($global_fields, $fields_config);
        }
    }

    public function build_fields($fields, $global_fields, $slug, $theme_colors)
    {
        $this->button_classes = apply_filters('acfgb_button_classes', $this->button_classes);

            if ($global_fields['button']) {
                $fields[$slug]
                    ->addGroup('button', [
                        'wrapper' => [
                            'width' => '100%',
                            'class' => 'acfgb-group',
                            'id' => 'acfgb-group-button',
                        ]
                    ])
                    ->addText('link',['default_value' => '#'])
                    ->addText('text',['default_value' => 'Click here'])
                    ->addSelect('class')
                        ->addChoices($this->button_classes)
                    ->addSelect('target',['label' => 'Open in'])
                        ->addChoices(['_self'=>'Same window','_blank'=>'New Window'])
                    ->endGroup();
            }
            if ($global_fields['bg_color']) {
                $fields[$slug]
                    ->addTab('Design', [
                        'wrapper' => [
                            'width' => '100%',
                            'class' => 'acfgb-tab acfgb-tab-design acfgb-tab-design-' . $slug,
                            'id' => 'acfgb-tab-design-' . $slug,
                        ]
                    ])
                    ->addGroup('section', [
                        'wrapper' => [
                            'width' => '100%',
                            'class' => 'acfgb-group',
                            'id' => 'acfgb-group-section',
                        ]
                    ])
                        ->addSelect('bg_color', ['allow_null' => 1, 'label' => 'Background Color'])
                            ->addChoices($theme_colors)
                        ->addSelect('text_color', ['allow_null' => 1])
                            ->addChoices($theme_colors)
                    ->endGroup()
                    ->addGroup('container', [
                        'wrapper' => [
                            'width' => '100%',
                            'class' => 'acfgb-group',
                            'id' => 'acfgb-group-container',
                        ]
                    ])
                    ->addSelect('bg_color', ['allow_null' => 1, 'label' => 'Background Color'])
                    ->addChoices($theme_colors)
                    ->endGroup();

            }

            if ($global_fields['block_id']) {
                $fields[$slug]
                    ->addTab('Class', [
                        'wrapper' => [
                            'width' => '100%',
                            'class' => 'acfgb-tab acfgb-tab-class acfgb-tab-class-' . $slug,
                            'id' => 'acfgb-tab-class-' . $slug,
                        ]
                    ])
                    ->addText('block_id');
            }

            if ($global_fields['block_classes']) {
                $fields[$slug]
                    ->addText('block_classes');
                if ($global_fields['button']) {
                    $fields[$slug]
                        ->addText('button_class');
                }
            }

            $fields[$slug]
                ->setLocation('block', '==', 'acf/' . $slug);

            return $fields;

        }

}

<?php

namespace ACF_Gutenberg\Includes;
use function Roots\wp_die;
use StoutLogic\AcfBuilder\FieldsBuilder;

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

class FieldsController
{

    public $button_classes = [
        'btn-primary'   =>'Primary',
        'btn-secondary' =>'Secondary'
    ];

    public $tab_fields = [
        'content'   => [],
        'design'    => ['section', 'container'],
        'class'     => ['custom_id','custom_class', 'custom_button_class'],
    ];

    public $classes = [
        'group'     => 'acfgb-group',
        'tab'       => 'acfgb-tab',
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
            $fields_replace = array_replace($global_fields, $fields_config);
            return $fields_replace;
        }
    }

    public function build_fields($custom_fields, $global_fields, $slug, $theme_colors, $field_options)
    {
        if (!isset($custom_fields['content']['fields'])){
            $custom_fields['content']['fields'] = new FieldsBuilder($slug);
        }

        if (!isset($custom_fields['design']['fields'])){
            $custom_fields['design']['fields'] = new FieldsBuilder($slug);
        }

        if (!isset($custom_fields['class']['fields'])){
            $custom_fields['class']['fields'] = new FieldsBuilder($slug);
        }


        $compatibility_mode = Lib\get_compatibility_mode();
        $this->button_classes = apply_filters('acfgb_button_classes', $this->button_classes);
        $fields[$slug] = new FieldsBuilder($slug);
        if($compatibility_mode){
            $fields = $this->get_compatibility_tabs($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);
        }else{
            $fields = $this->get_content_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);
            $fields = $this->get_design_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);
            $fields = $this->get_class_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);
        }
        $fields[$slug]->setLocation('block', '==', 'acf/' . $slug);
        return $fields;
     }


    public function get_content_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options){

        $button_group = $this->set_button($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);

        $custom_global_fields = [];
        $custom_global_fields = apply_filters('acfgb_content_global_fields', $custom_global_fields);
        $fields[$slug]
            ->addTab('Content', [
                'wrapper' => [
                    'width' => '100%',
                    'class' => 'acfgb-tab acfgb-tab-content acfgb-tab-content-' . $slug,
                    'id' => 'acfgb-tab-content-' . $slug,
                ]
            ])
                ->addFields( $custom_fields['content']['fields'] )
                ->addFields( $button_group )
                ->addFields( $custom_global_fields );

        return $fields;
    }

    public function get_design_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options){
        if ($this->tab('design',$global_fields)) {

            $section_group = $this->set_section($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);

            $custom_global_fields = [];
            $custom_global_fields = apply_filters('acfgb_design_global_fields', $custom_global_fields);
            $fields[$slug]
                ->addTab('Design', [
                    'wrapper' => [
                        'width' => '100%',
                        'class' => 'acfgb-tab acfgb-tab-design acfgb-tab-design-' . $slug,
                        'id' => 'acfgb-tab-design-' . $slug,
                    ]
                ])
                    ->addFields( $section_group )
                    ->addFields( $custom_fields['design']['fields'] )
                    ->addFields( $custom_global_fields );

        }
        return $fields;
    }

    public function get_class_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options){
        if ($this->tab('class',$global_fields)) {
            $classes_group= $this->set_classes($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);

            $fields[$slug]
                ->addTab('Class', [
                    'wrapper' => [
                        'width' => '100%',
                        'class' => 'acfgb-tab acfgb-tab-class acfgb-tab-class-' . $slug,
                        'id' => 'acfgb-tab-class-' . $slug,
                    ]
                ])
                    ->addFields( $classes_group )
                    ->addFields( $custom_fields['class']['fields'] );
        }
        return $fields;
    }




    public function set_button($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options){
        $button_group = new FieldsBuilder($slug.'-button-group');
            if ($global_fields['button']) {
                $button_fields = new FieldsBuilder($slug.'-button-fields');
                $button_fields
                    ->addText('link',['default_value' => '#'])
                    ->addText('text',['default_value' => 'Click here']);
                if ($global_fields['button_class']) {
                    $button_fields
                        ->addSelect('class')
                            ->addChoices($this->button_classes)
                        ->addText('custom_classes', [
                            'default_value' => 'btn'
                        ])
                            ->conditional('class', '==', 'custom');
                }
                if ($global_fields['button_target']) {
                    $button_fields
                        ->addSelect('target',['label' => 'Open in'])
                            ->addChoices(['_self'=>'Same window','_blank'=>'New Window']);
                }
                if ($global_fields['button_icon']) {
                    $button_fields
                        ->addTrueFalse('icon',['default_value' => 0, 'ui' => 1]);
                }

                $button_group
                    ->addGroup('button', [
                        'wrapper' => [
                            'width' => '100%',
                            'class' => 'acfgb-group acfgb-group-button acfgb-group-button-'.$slug,
                            'id' => 'acfgb-group-button',
                        ]
                    ])
                        ->addFields( $button_fields )
                    ->endGroup('button');
            }
            return $button_group;
        }

    public function set_section($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options){
        if ($global_fields['section']){
            $section_fields = new FieldsBuilder($slug.'-section-fields');
            if ($global_fields['bg_color']) {
                $section_fields
                    ->addSelect('bg_color', ['allow_null' => 1, 'label' => 'Background Color'])
                        ->addChoices($theme_colors);
            }
            if ($global_fields['text_color']) {
                $section_fields
                    ->addSelect('text_color', ['allow_null' => 1])
                        ->addChoices($theme_colors);
            }

            if ($global_fields['full_height']) {
                $section_fields
                    ->addTrueFalse('full_height', ['ui' => 1]);
            }

            $section_group = new FieldsBuilder($slug.'-section-group');
            $section_group
                ->addGroup('section', [
                    'wrapper' => [
                        'width' => '100%',
                        'class' => 'acfgb-group acfgb-group-section acfgb-group-section-'.$slug,
                        'id' => 'acfgb-group-section',
                    ]
                ])
                    ->addFields( $section_fields )
                ->endGroup();
        }

        return $section_group;
    }

    public function set_classes($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options){

        $classes_fields = new FieldsBuilder($slug.'-classes-fields');

        if ($global_fields['custom_id']) {
            $classes_fields->addText('custom_id');
        }
        if ($global_fields['custom_class']) {
            $classes_fields->addText('block_classes');
        }
        return $classes_fields;

    }

    public function tab($tab, $global_fields){
        $tab_status = false;
        foreach ($global_fields as $key => $value) {
            if ($tab_status == false) {
                if($value){
                    if (in_array($key, $this->tab_fields[$tab])){
                        $tab_status = true;
                    }
                }
            }
        }
        return $tab_status;
    }




    public function get_compatibility_tabs($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options){


        // SET CONTENT TAB
        $custom_global_fields = [];
        $custom_global_fields = apply_filters('acfgb_content_global_fields', $custom_global_fields);
        $fields[$slug]
            ->addTab('Content', [
                'wrapper' => [
                    'width' => '100%',
                    'class' => 'acfgb-tab acfgb-tab-content acfgb-tab-content-' . $slug,
                    'id' => 'acfgb-tab-content-' . $slug,
                ]
            ]);
        $button_group = $this->set_button($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);

        $fields[$slug]
            ->addGroup('content', [
                'wrapper' => [
                    'width' => '100%',
                    'class' => 'acfgb-group acfgb-group-section acfgb-group-section-'.$slug,
                    'id' => 'acfgb-group-section',
                ]
            ])
            ->addFields( $custom_fields['content']['fields'] )
            ->addFields( $button_group )
            ->addFields( $custom_global_fields )
            ->endGroup();


        // SET DESIGN TAB
        if ($this->tab('design',$global_fields)) {
            $custom_global_fields = [];
            $custom_global_fields = apply_filters('acfgb_design_global_fields', $custom_global_fields);
            $fields[$slug]
                ->addTab('Design', [
                    'wrapper' => [
                        'width' => '100%',
                        'class' => 'acfgb-tab acfgb-tab-design acfgb-tab-design-' . $slug,
                        'id' => 'acfgb-tab-design-' . $slug,
                    ]
                ]);
            $section_group = $this->set_section($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);

            $fields[$slug]
                ->addGroup('design', [
                    'wrapper' => [
                        'width' => '100%',
                        'class' => 'acfgb-group acfgb-group-section acfgb-group-section-'.$slug,
                        'id' => 'acfgb-group-section',
                    ]
                ])
                ->addFields( $section_group )
                ->addFields( $custom_fields['design']['fields'] )
                ->addFields( $custom_global_fields )
                ->endGroup();

        }

        // SET CLASS TAB
        if ($this->tab('class',$global_fields)) {
            $fields[$slug]
                ->addTab('Class', [
                    'wrapper' => [
                        'width' => '100%',
                        'class' => 'acfgb-tab acfgb-tab-class acfgb-tab-class-' . $slug,
                        'id' => 'acfgb-tab-class-' . $slug,
                    ]
                ]);

            $classes_group= $this->set_classes($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options);
            $fields[$slug]
                ->addGroup('custom_classes', [
                    'wrapper' => [
                        'width' => '100%',
                        'class' => 'acfgb-group acfgb-group-section acfgb-group-section-'.$slug,
                        'id' => 'acfgb-group-section',
                    ]
                ])
                ->addFields( $classes_group )
                ->addFields( $custom_fields['class']['fields'] )
                ->endGroup();
        }

        return $fields;
    // END COMPATIBILITY MODE
    }


}

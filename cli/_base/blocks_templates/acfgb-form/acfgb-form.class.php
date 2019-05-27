<?php

namespace ACF_Gutenberg\Blocks;

use ACF_Gutenberg\Classes\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class AcfgbForm extends Block
{
    public $block_title = 'ACFGB Form';
    public $icon = 'edit';

    public $fields_config = [
        // DESIGN TAB
        'section' => true,
        'bg_color' => true,
        'text_color' => true,
        'container' => true,

        // CLASS TAB
        'custom_id' => true,
        'custom_class' => true,
        'custom_button_class' => true,
    ];

    public function init()
    {
        $this->cf7 = $this->get_cf7_shortcode();
    }


    public function set_fields()
    {
        $tabs['content']['fields'] = new FieldsBuilder($this->slug);
        $tabs['content']['fields']
            ->addText('title',[
                'default_value' => 'Form Title'
            ])
            ->addTextarea('intro',[
                'default_value' => $this->get_ipsum()
            ])
            ->addTrueFalse('custom_shortcode', [
                'default_value' => 0,
                'ui' => 1,
            ])
            ->addSelect('form_shortcode_cf7',[
                'choices' => $this->get_cf7_forms(),
                'conditional_logic' => [
                    [
                        [
                            'field' => 'custom_shortcode',
                            'operator' => '==',
                            'value' => '0',
                        ],
                    ],
                ]
            ])
            ->addText('form_shortcode',[
                'default_value' => 'Paste shortcode',
                'conditional_logic' => [
                    [
                        [
                            'field' => 'custom_shortcode',
                            'operator' => '==',
                            'value' => '1',
                        ],
                    ],
                ]
            ]);
        return $tabs;
    }

    public function get_cf7_forms()
    {
        $args = [
            'posts_per_page' => -1,
            'post_type' => 'wpcf7_contact_form',
        ];

        $cf7_forms_query = new \WP_Query($args);
        $cf7_forms = [];
        if ( $cf7_forms_query->have_posts() ) {
            while ( $cf7_forms_query->have_posts() ) { $cf7_forms_query->the_post();
                $cf7_forms[get_the_title().'|-|'.get_the_ID()] = get_the_title();
            }
            wp_reset_postdata();
        } else {
            $cf7_forms = null;
        }

        return $cf7_forms;

    }
    public function get_cf7_shortcode()
    {
        $shortcode = false;
        if (isset($this->form_shortcode_cf7) && !empty($this->form_shortcode_cf7)){
            $cf7_form = explode('|-|',$this->form_shortcode_cf7);
            $shortcode = '[contact-form-7 id="'.$cf7_form[1].'" title="'.$cf7_form[0].'"].';
        }
        return $shortcode;
    }

}

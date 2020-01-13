<?php

namespace ACF_Gutenberg\Includes;
use function Roots\wp_die;
use StoutLogic\AcfBuilder\FieldsBuilder;
use ACF_Gutenberg\Includes\Lib;

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

    public function build_fields($custom_fields, $global_fields, $slug, $theme_colors, $field_options, $templates)
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
            $fields = $this->get_content_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options, $templates);
            $fields = $this->get_design_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options, $templates);
            $fields = $this->get_class_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options, $templates);
        }
        $fields[$slug]->setLocation('block', '==', 'acf/' . $slug);
        return $fields;
     }


    public function get_content_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options, $templates){

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
            ]);

		if ( is_array( $templates ) && count( $templates ) > 0 ) {
			$fields[$slug]
				->addSelect('template', [
					'choices' => $templates,
				]);
		}

        $fields[$slug]
        	->addFields( $custom_fields['content']['fields'] )
            ->addFields( $button_group )
            ->addFields( $custom_global_fields );


        return $fields;
    }

    public function get_design_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options, $templates){
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

    public function get_class_tab($custom_fields, $fields, $global_fields, $slug, $theme_colors, $field_options, $templates){
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



	public function register_modules (){


    	$wrapper = [
			'width' => '100%',
		];



		/**
		 * -------------------------------------
		 * SINGLE OPTIONS
		 * -------------------------------------
		 */

		/**
		 * Option: Link
		 */
		$o__link = new FieldsBuilder( 'o__link' );
		$o__link
			->addLink('link');

		/**
		 * Option: Video
		 */
		$o__video = new FieldsBuilder( 'o__video' );
		$o__video
			->addOembed('video');


		/**
		 * Option: Background Color
		 */
		$o__bg_color = new FieldsBuilder( 'o__bg_color' );
		$o__bg_color
			->addSelect('bg_color', [
				'label' => 'Background color',
				'wrapper' => $wrapper,
				'allow_null' => 1,
				'choices' => Lib\config( 'builder.bg_color' ),
			]);


		/**
		 * Option: Text align
		 */
		$o__text_align = new FieldsBuilder( 'o__text_align' );
		$o__text_align
			->addSelect('text_align', [
				'label' => 'Text align',
				'allow_null' => 1,
				'choices' => Lib\config( 'builder.text_align' ),
			]);

		/**
		 * Option: Text color
		 */
		$o__text_color = new FieldsBuilder( 'o__text_color' );
		$o__text_color
			->addSelect('text_color', [
				'label' => 'Text color',
				'allow_null' => 1,
				'choices' => Lib\config( 'builder.text_color' ),
			]);

		/**
		 * Option: Text font
		 */
		$o__text_font = new FieldsBuilder( 'o__text_font' );
		$o__text_font
			->addSelect('text_font', [
				'label' => 'Text font',
				'allow_null' => 1,
				'choices' => Lib\config( 'builder.text_font' ),
			]);

		/**
		 * Option: Heading tag
		 */
		$o__heading_tag = new FieldsBuilder( 'o__heading_tag' );
		$o__heading_tag
			->addSelect('tag', [
				'label' => 'Type',
				'wrapper' => [
//						'class' => 'select2 select2-hidden-accessible',
				],
				'choices' => Lib\config( 'builder.heading_tag' ),
			]);

		/**
		 * Option: Padding
		 */
		$o__padding = new FieldsBuilder( 'o__padding' );
		$o__padding
			->addTrueFalse('custom_padding', ['ui' => 1] )
			->addSelect('padding', [
				'label' => 'Padding',
				'allow_null' => 1,
				'choices' => Lib\config( 'builder.padding' ),
			]);


		/**
		 * Option: Marging
		 */
		$o__margin = new FieldsBuilder( 'o__margin' );
		$o__margin
			->addTrueFalse('custom_margin', ['ui' => 1])
			->addSelect('margin', [
				'label' => 'Margin',
				'allow_null' => 1,
				'choices' => Lib\config( 'builder.margin' ),
			]);


		/**
		 * Option: Shadow
		 */
		$o__shadow = new FieldsBuilder( 'o__shadow' );
		$o__shadow
			->addSelect('shadow', [
				'label' => 'Shadow',
				'allow_null' => 1,
				'choices' => Lib\config( 'builder.shadow' ),
			]);



		/**
		 * -------------------------------------
		 * OPTION GROUPS
		 * -------------------------------------
		 */

		/**
		 * Option Group: Background
		 */
		$g__background = new FieldsBuilder( 'g__background' );
		$g__background
			->addAccordion('background_options', [
				'label' => 'Background Options',
				'wrapper' => $wrapper,
			])
				->addFields( $o__bg_color )
			->addAccordion('background_options_end')->endpoint();

		/**
		 * Option Group: Text
		 */
		$g__text = new FieldsBuilder( 'g__text' );
		$g__text
			->addAccordion('text_options', [
				'label' => 'Text Options',
				'wrapper' => $wrapper,
			])
				->addFields( $o__text_align )
				->addFields( $o__text_color )
				->addFields( $o__text_font )
			->addAccordion('text_options_end')->endpoint();

		/**
		 * Option Group: Spacings
		 */
		$g__spacings = new FieldsBuilder( 'g__spacings' );
		$g__spacings
			->addAccordion('spacings_options', [
				'label' => 'Spacings Options',
				'wrapper' => $wrapper,
			])
				->addFields( $o__padding )
				->addFields( $o__margin )
			->addAccordion('spacings_options_end')->endpoint();

		/**
		 * Option Group: Shadow
		 */
		$g__shadow = new FieldsBuilder( 'g__shadow' );
		$g__shadow
			->addAccordion('shadow_options', [
				'label' => 'Shadow Options',
				'wrapper' => $wrapper,
			])
				->addFields( $o__shadow )
			->addAccordion('shadow_options_end')->endpoint();

    	/**
		 * Option Group: Link
		 */
		$g__link = new FieldsBuilder( '__link' );
		$g__link
			->addAccordion('link', [
				'label' => 'Link',
				'wrapper' => $wrapper,
			])
				->addFields( $o__link )
			->addAccordion('link_end')->endpoint();



		/**
		 * -------------------------------------
		 * COMPONENTS
		 * -------------------------------------
		 */

		/**
		 * Component: Image
		 */
    	$c__image = new FieldsBuilder( 'c__image' );
    	$c__image
			->addAccordion('image', [
				'label' => 'Image',
				'wrapper' => $wrapper,
			])
				->addImage( 'image', [
					'return_format' => 'array',
				])
				->addTrueFalse('use_caption', [
					'default_value' => 0,
					'ui' => 1,
				])
				->addText('caption')
					->conditional('use_caption', '==', '')
				->addSelect('aspect_ratio', [
					'label' => 'Aspect ratio',
					'choices' => [
						'image-original'  => 'Original',
						'image-square'    => 'Square',
						'image-landscape' => 'Landscape',
						'image-vertical'  => 'Vertical',
					],
				])
				->addAccordion('image_end')->endpoint()
			->addFields( $g__link );

		/**
		 * Component: Button
		 */
		$c__button = new FieldsBuilder( 'c__button' );
		$c__button
			->addAccordion('button', [
				'wrapper' => $wrapper,
			])
				->addFields( $o__link )
				->addSelect('style', [
					'choices' => Lib\config( 'builder.button' ),
				])
			->addAccordion('button_end')->endpoint();



		/**
		 * -------------------------------------
		 * MODULES
		 * -------------------------------------
		 */

		/**
		 * Module: Button
		 */
		$m__button = new FieldsBuilder( 'button' );
		$m__button
			->addFields( $c__button );

		/**
		 * Module: Video
		 */
		$m__video = new FieldsBuilder( 'video' );
		$m__video
			->addFields( $o__video );

		/**
		 * Module: Image
		 */
		$m__image = new FieldsBuilder( 'image' );
		$m__image
			->addFields( $c__image );

		/**
		 * Module: Heading
		 */
		$m__heading = new FieldsBuilder( 'heading' );
		$m__heading
			->addTab('content')
				->addText('title')
				->addFields( $o__heading_tag )
			->addTab('design')
				->addFields( $g__background )
				->addFields( $g__text )
				->addFields( $g__spacings );

		/**
		 * Module: Member
		 */
		$m__member = new FieldsBuilder( 'member' );
		$m__member
			->addTab('content')
				->addFields( $c__image )
				->addText('name')
				->addText('position')
				->addText('company')
			->addTab('design')
				->addFields( $g__background )
				->addFields( $g__text )
				->addFields( $g__spacings )
				->addFields( $g__shadow );

		/**
		 * Module: Banner
		 */
		$m__banner = new FieldsBuilder( 'banner' );
		$m__banner
			->addTab('content')
				->addFields( $c__image )
				->addAccordion('content', [
					'wrapper' => $wrapper,
				])
					->addWysiwyg('content')
				->addAccordion('content_end')->endpoint()
			->addTab('design')
				->addFields( $g__background )
				->addFields( $g__spacings )
				->addFields( $g__shadow );


		/**
		 * Module: Text
		 */
		$m__text = new FieldsBuilder( 'text' );
		$m__text
			->addText('title')
			->addWysiwyg('content' );



		/**
		 * SET MODULES FOR FLEXIBLE BLOCK
		 */
		$column = new FieldsBuilder( 'column' );
		$column
			->addFlexibleContent('modules')
			->addLayout($m__heading)
			->addLayout($m__button)
			->addLayout($m__video)
			->addLayout($m__member)
			->addLayout($m__text)
			->addLayout($m__banner);

		return $column;
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

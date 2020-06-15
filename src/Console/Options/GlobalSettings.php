<?php

namespace App\Options;

use AcfGutenberg\Options as Field;
use StoutLogic\AcfBuilder\FieldsBuilder;

class GlobalSettings extends Field
{
    /**
     * The option page menu name.
     *
     * @var string
     */
    public $name = 'Global Settings';

    /**
     * The option page document title.
     *
     * @var string
     */
    public $title = 'GlobalSettings | Options';

    /**
     * The option page field group.
     *
     * @return array
     */
    public function fields()
    {
        $globalSettings = new FieldsBuilder('global_settings');

        $globalSettings
            ->addTab('header', ['placement' => 'left'])
                ->addImage('logo',[
                    'wrapper' => ['width' => '33%']
                ])
                ->addImage('logo_white',[
                    'wrapper' => ['width' => '33%']
                ])

            ->addTab('blog', ['placement' => 'left'])
                ->addPostObject('featured_post',[
                    'post_type' => ['post'],
                ])
            ->addText('load_more_label')
                ->addLink('all_posts')
            ->addTab('call_to_action', ['placement' => 'left'])
                ->addImage('cta_image', [
                    'preview_size' => 'large',
                ])
                ->addTextarea('cta_title', [
                    'default_value' => '',
                ])
                ->addLink('cta_button', [
                    'return_format' => 'array', /* 'array' || 'id' */
                ])
            ->addTab('icons',['placement' => 'left'])
                ->addImage('play_icon', [
                    'return_format' => 'array', /* 'array' || 'id' || 'url' */
                    'preview_size' => 'medium',
                ])
            ->addTab('social', ['placement' => 'left'])
                ->addText('social_title')
                ->addGroup('social_profiles')
                    ->addUrl('linkedin')
                    ->addUrl('facebook')
                    ->addUrl('instagram')
                    ->addUrl('twitter')
                    ->addUrl('youtube')
                ->endGroup()

            ->addTab('footer', ['placement' => 'left'])
                ->addGroup('footer_col_1',[
                    'wrapper' => ['width' => '33%']
                ])
                    ->addImage('logo')
                    ->addWysiwyg('text')
            ->endGroup()

            ->addGroup('footer_col_2',[
                'wrapper' => ['width' => '33%'],
                'instructions' => 'You can set the footer menus from the <a href="/wp/wp-admin/nav-menus.php">Menu Page</a>',
            ])

            ->endGroup()
            ->addGroup('footer_col_3',[
                        'wrapper' => ['width' => '33%']
                    ])
                    ->addWysiwyg('text')
            ->endGroup();

        return $globalSettings->build();
    }
}

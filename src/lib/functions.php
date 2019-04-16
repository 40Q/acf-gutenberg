<?php

namespace ACF_Gutenberg\Lib;
use ACF_Gutenberg\Plugin;

/**
 * @param string $view
 * @param array $attributes
 */
function render_plugin_view($view, array $attributes = [])
{
    echo $GLOBALS['plugin_blade_engine']->view()->make($view, $attributes);
}

/**
 * @param string $view
 * @param array $attributes
 */
function render_theme_view($view, array $attributes = [])
{
    echo $GLOBALS['theme_blade_engine']->view()->make($view, $attributes);
}

/**
 * @param string $view
 * @param array $attributes
 *
 * @return string
 */
function get_rendered_blade_view($view, array $attributes = [])
{
    return $GLOBALS['blade_engine']->view()->make($view, $attributes);
}

/**
 * Modify
 */
function convert_to_class_name($str)
{
    $str = ucwords(str_replace('-', ' ', $str));
    $str = ucwords(str_replace('_', ' ', $str));
    return str_replace(' ', '', $str);
}


/**
 * Modify
 */
function do_actions($actions, $class = false, $prefix = null)
{
    foreach ($actions as $action):
        if (is_array($action) && count($action) >= 3){
            // Action is a class method
            wp_die('Actions must have 1 or 2 index');
        }elseif (is_array($action) && count($action) == 2){
            // Action has defined custom hook
            if ($class === false){
                add_action($action[0], $action[1]);
            }else{
                add_action($action[0], [$class, $action[1]]);
            }
        }else{
            if (is_array($action)){
                $action = $action[0];
            }
            if ($class === false){
                add_action($prefix.$action, $action);
            }else{
                add_action($prefix.$action, [$class, $action]);
            }
            do_action($prefix.$action);
        }
    endforeach;
}


function my_acf_block_render_callback($block)
{
    \ACF_Blocks::my_acf_block_render_callback($block);
}



/**
 * Modify
 */
function test()
{
    wp_die('here');
}
//test();


add_action( 'admin_menu', function (){
    add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', function (){
        echo '<div class="wrap">';
        echo '<p>Here is where the form would go if I actually had options.aaa</p>';
        echo '</div>';
        echo '<pre>';
        //print_r(Plugin::get_actions());
        echo '</pre>';
        echo '<pre>';
        print_r($GLOBALS['theme_blade_engine']);
        echo '</pre>';
    } );
});
<?php
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AcfgbCommand extends Command
{

    protected $css_class_prefix = "b-";
    protected $blocks_templates_dir = __DIR__."/_base/blocks_templates";
    protected $block_base_dir = __DIR__."/_base/_block-base";
    protected $js_base_file = __DIR__."/_base/route.js";
    protected $block_cli_file = __DIR__."/_base/block";
    protected $plugin_blocks_dir = __DIR__ .'/../resources/blocks/';
    protected $theme_path = "";
    protected $theme_plugin_dir = '';
    protected $theme_blocks_dir = '';
    protected $block_labels = [];
    protected $target;
    protected $output;
    protected $input;
    protected $fileManager;
    protected $default_messages = [
        'tasks_ready' => "------ All task ready ------"
    ];

    protected function execute(InputInterface $input, OutputInterface $output){
        $this->output = $output;
        $this->input = $input;
        $this->input = $input;
        $this->fileManager = new FileManager();
        $this->theme_plugin_dir = get_template_directory() . '/acf-gutenberg/';
        $this->theme_blocks_dir = get_template_directory() . '/acf-gutenberg/blocks/';
        $this->set_target($this->input);
        $this->initial_setting();
        $this->command_init();
    }

    protected function command_init(){
        // Use this method in extended classes
    }

    /*
     *  ---------------------------------------------------------------------------------------------
     *                                          SETTER
     *  ---------------------------------------------------------------------------------------------
     */

    public function initial_setting(){
        if (isset($this->commandArgumentName)){
            $name = $this->input->getArgument($this->commandArgumentName);
        }else if (isset($this->commandArgumentPrefix)){
            $name = $this->input->getArgument($this->commandArgumentPrefix)."-".$this->input->getArgument($this->commandArgumentBlock);
        }else{
            $name = false;
        }
        if (isset($this->commandArgumentBlock)){
            $block = $this->input->getArgument($this->commandArgumentBlock);
            $prefix = false;
            if (isset($this->commandArgumentPrefix)){
                $prefix = $this->input->getArgument($this->commandArgumentPrefix);
            }
            $name = $this->set_name_by_prefix($block, $prefix);
        }
        $this->set_block_labels($name);
        $this->print('a');
        $this->print($this->commandArgumentPrefix);
    }

    public function set_target($input){
        $this->target = $this->get_target($input);
    }

    public function set_theme_target(){
            $this->theme_path = get_template_directory();
        if (isset($this->optionTheme) && $this->input->getOption($this->optionTheme) != '') {
            $this->theme_path = $this->get_theme_target();
        }
    }

    public function set_block_labels($name){
        $this->block_labels = $this->get_block_labels($name);
    }

    public function set_name_by_prefix($block, $prefix){
        $new_block_name = $block;
        if ($prefix){
            $new_block_name = $prefix."-".$block;
        }
        return $new_block_name;
    }

    public function fileManager(){
        return $this->fileManager;
    }


    /*
     *  ---------------------------------------------------------------------------------------------
     *                                          TASKS
     *  ---------------------------------------------------------------------------------------------
     */


    public function rename_block_base_php_class($file, $php_class, $title = false){
        $error = $this->fileManager()->edit_file(
            'replace',
            $file,
            [
                'BlockBaseTitle' => $title,
                'BlockBase'      => $php_class,
             ],
            'rename php class'
        );
        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ PHP Class was replaced: {$php_class}",
                'info');
            $this->print(
                " ✓ PHP Title was replaced: {$title}",
                'info');
        }
    }

    public function rename_template_php_class($file, $php_class, $title = false){
        $error = $this->fileManager()->edit_file(
            'replace',
            $file,
            [
                'ACFGB' => ucfirst($this->block_labels->prefix),
                'Acfgb' => ucfirst($this->block_labels->prefix),
            ],
            'rename php class'
        );
        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ PHP Class was replaced: {$php_class}",
                'info');
            $this->print(
                " ✓ PHP Title was replaced: {$title}",
                'info');
        }
    }

    public function rename_clone_php_class($file, $block_to_clone){
        $block_to_clone = $this->get_block_labels($block_to_clone);
        $error = $this->fileManager()->edit_file(
            'replace',
            $file,
            [
                $block_to_clone->php_class => ucfirst($this->block_labels->php_class),
                $block_to_clone->title => ucfirst($this->block_labels->title),
                ucwords($block_to_clone->title) => ucfirst($this->block_labels->title),
            ],
            'rename clone php class'
        );
        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ PHP Class was replaced: {$this->block_labels->php_class}",
                'info');
            $this->print(
                " ✓ PHP Title was replaced: {$this->block_labels->title}",
                'info');
        }
    }

    public function rename_block_base_css_class($file, $css_class){

        $error = $this->fileManager()->edit_file(
            'replace',
            $file,
            [
                'b-block-base' => $css_class,
            ],
            'rename css class'
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ CSS class was replaced: {$css_class}",
                'info');
        }
    }

    public function rename_template_css_class($file, $css_class){

        $error = $this->fileManager()->edit_file(
            'replace',
            $file,
            [
                'acfgb' => $this->block_labels->prefix,
                '--' => '-',
            ],
            'rename css class'
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ CSS class was replaced: {$css_class}",
                'info');
        }
    }

    public function rename_clone_css_class($file, $block_to_clone){
        $error = $this->fileManager()->edit_file(
            'replace',
            $file,
            [
                $block_to_clone => $this->block_labels->slug,
                '--' => '-',
            ],
            'rename clone css class'
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ CSS class was replaced: {$this->block_labels->css_class}",
                'info');
        }
    }

    public function clone_block($block_to_clone, $target){
        $error = $this->fileManager()->copy_dir(
            $target."/".$block_to_clone,
            $target,
            $this->block_labels->slug,
            'clone block'
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ Block cloned in {$this->target} folder: /{$this->block_labels->slug}/",
                'info');
        }
    }



    public function import_block_base($blocks_dir){
        $error = $this->fileManager()->copy_dir(
            $this->block_base_dir,
            $blocks_dir,
            $this->block_labels->slug,
            'import block base'
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ New block created in {$this->target} folder: /{$this->block_labels->slug}/",
                'info');
        }
    }

    public function import_template($template, $target){
        $error = $this->fileManager()->copy_dir(
            $this->blocks_templates_dir."/".$template,
            $target,
            $this->block_labels->slug,
            'import template'
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ Block imported in {$this->target} folder: /{$this->block_labels->slug}/",
                'info');
        }
    }


    public function import_js($blocks_dir){
        $error = $this->fileManager()->copy_file(
            $this->js_base_file,
            $blocks_dir.$this->block_labels->slug.'/',
            $this->block_labels->js_file.".js",
            'import js file base'
        );
        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ Js file base created in {$this->target} folder",
                'info');
            $this->print(
                " ! REMEMBER add this file to you JS routes file, like: ",
                'comment');
            $this->print(
                " --> import {$this->block_labels->js_file} from '../../acf-gutenberg/blocks/{$this->block_labels->slug}/{$this->block_labels->js_file}';");
        }

    }

    public function import_block_cli_file($blocks_dir){
        if (strpos($blocks_dir, 'resources')){
            $blocks_dir = str_replace('resources', '', $blocks_dir);
        }
        $error = $this->fileManager()->copy_file(
            $this->block_cli_file,
            $blocks_dir,
            "block",
            'import block cli file'
        );
        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ Block CLI file imported in theme directory",
                'info');
        }

    }


    public function add_block_styles_to_blocks_scss($block_scss_file){
        $blocks_scss_file = $this->get_target_path().'../../assets/styles/blocks.scss';

        $error = $this->fileManager()->edit_file(
            'add_to_bottom',
            $blocks_scss_file,
            [
                '@import "/../../acf-gutenberg/blocks/'.$this->block_labels->slug."/".$this->block_labels->scss_file.'";'
            ],
            'import block css in blocks.scss'
        );

        if ($error){
            $this->print($error, 'error');
        }else{
            $this->print(
                " ✓ Block css was imported in blocks.scss",
                'info');
        }

    }

    public function block_exist($slug, $target = false){
        $block_exist = false;
        if ($target && $target == "plugin"){
            if (is_dir("$this->plugin_blocks_dir/$slug")){
                $block_exist = true;
            }
        }else if ($target && $target == "theme"){
            $blocks_dir = $this->get_target_path();
            if (is_dir("$blocks_dir/$slug")) {
                $block_exist = true;
            }
        }else{
            $blocks_dir = $this->get_target_path();
            if (is_dir("$this->plugin_blocks_dir/$slug") || is_dir("$blocks_dir/$slug")) {
                $block_exist = true;
            }
        }
        return $block_exist;
    }

    public function block_template_exist($slug){
        $template_exist = false;
        if (is_dir("$this->blocks_templates_dir/$slug") || is_dir("$this->blocks_templates_dir/acfgb-$slug")) {
            $template_exist = true;
        }
        return $template_exist;
    }


    public function name_to_slug($str){
        $str = str_replace('_', '-', $str);
        $str = str_replace(' ', '-', $str);
        $str = strtolower($str);
        return $str;
    }

    public function name_to_php_class($str)
    {
        $str = ucwords(str_replace('-', ' ', $str));
        $str = ucwords(str_replace('_', ' ', $str));
        return str_replace(' ', '', $str);
    }

    public function name_to_css_class($str)
    {
        $str = ucwords(str_replace('_', '-', $str));
        $str = ucwords(str_replace(' ', '-', $str));
        $str = strtolower($str);
        return $this->css_class_prefix.$str;
    }

    public function name_to_title($str)
    {
        $str = str_replace('_', ' ', $str);
        $str = str_replace('-', ' ', $str);
        $str = ucfirst(strtolower($str));
        return $str;
    }

    public function slug_to_css_file($str)
    {
        $str = ucwords(str_replace('-', '_', $str));
        $str = ucwords(str_replace(' ', '_', $str));
        $str = strtolower($str);
        if (mb_substr($str,0,1) != '_'){
            $str = "_".$str;
        }
        return $str;
    }

    public function slug_to_js_file($str)
    {
        $str_temp = explode('-', $str);
        $str = '';
        foreach ($str_temp as $word){
            $str.= ucfirst($word);
        }
        return $str;
    }


    public function print($message , $style = false){
        if($style){
            $message = $this->get_formatted_message($message, $style);
        }
        $this->output->writeln($message);
    }
    public function the_blocks_list(){
        $blocks = $this->get_blocks_templates();
        $text = 'Available blocks to import';
        $i = 0;
        foreach ($blocks as $block){
        $i++;
        $text.= "\n";
        $text.= "  ".$i.". ".$block;
        }
        $this->print($text);
    }


    public function is_active_theme(){
        $is_active_theme = true;
        $active_theme = $this->get_theme_root($this->theme_blocks_dir);
        $bash_dir = getcwd();
        $active_theme = $this->add_barra($active_theme);
        $bash_dir = $this->add_barra($bash_dir);
        if ($bash_dir != $active_theme){
            $is_active_theme = false;
        }
//        $this->print($active_theme);
//        $this->print($bash_dir);

        return $is_active_theme;
    }


    public function add_barra($path){
        if (substr($path, -1) != "/" ){
            $path.= '/';
        }
        return $path;
    }


    /*
     *  ---------------------------------------------------------------------------------------------
     *                                          GETTER
     *  ---------------------------------------------------------------------------------------------
     */

    public function get_formatted_message($message, $style){
        switch ($style){
            case 'comment':
                $tag = 'comment';
                break;
            case 'info':
                $tag = 'info';
                break;
            case 'error':
                $tag = 'error';
                break;
        }
        $message = "<{$tag}>{$message}</{$tag}>";
        return $message;
    }



    public function get_target(InputInterface $input){
        $target = 'theme';
        if (isset($this->optionTarget) && $input->getOption($this->optionTarget) == 'plugin') {
            $target = 'plugin';
        }
        return $target;
    }

    public function get_target_path(){
        if ($this->target == 'plugin') {
            $path = $this->plugin_blocks_dir;
        }else{
            $path = $this->theme_blocks_dir;
            if (!$this->is_active_theme()){
                $path = $this->get_current_blocks_dir();
                if (!$path){
                    $this->print("Can not create block: Invalid path", 'error');
                }
            }
        }
        return $path;
    }
    public function get_theme_root($path){
        if (strpos($path, 'acf-gutenberg/blocks/')){
            $path = str_replace('acf-gutenberg/blocks/', '', $path);
        }
        if (strpos($path, 'resources/')){
            $path = str_replace('resources/', '', $path);
        }
        return $path;
    }

    public function get_theme_target(){
        $theme_path = false;
        if (isset($this->optionTheme) && $this->input->getOption($this->optionTheme) != '') {
            $custom_theme = $this->input->getOption($this->optionTheme);
            if(defined('WP_CONTENT_DIR')){
                $themes_dir = WP_CONTENT_DIR;
                if (is_dir($themes_dir."/themes/".$custom_theme)){
                    $theme_path = $themes_dir."/themes/".$custom_theme;
                }else{
                    $this->print("Theme: {$custom_theme} not found",'error');
                }
            }else{
                $this->print("WP_CONTENT_DIR is not defined to search custom theme: {$custom_theme}",'error');
            }
        }
        return $theme_path;
    }

    public function get_current_blocks_dir(){
        $path = getcwd();
        $path = $this->add_barra($path);
        if (is_dir($path."acf-gutenberg/blocks/")){
            $path = $path."acf-gutenberg/blocks/";
        }else if (is_dir($path."resources/acf-gutenberg/blocks/")){
            $path = $path."resources/acf-gutenberg/blocks/";
        }else{
            $this->print("acf-gutenberg folder dont exist in this theme", "error");
            $path = false;
        }
        return $path;
    }


    public function get_blocks($target = false){
        $blocks_paths = [
            'plugin' => $this->plugin_blocks_dir,
            'theme' => $this->get_target_path(),
        ];
        if ($target){
            foreach ($blocks_paths as $key => $path){
                if ($key != $target){
                    unset($blocks_paths[$key]);
                }
            }
        }

        $blocks_list = array();
        if (is_array($blocks_paths)) {
            foreach ($blocks_paths as $key => $path) {
                $blocks_list[] = " ->Blocks in ".$key;
                if (is_dir($path)) {
                    $blocks = array_diff(scandir($path), ['..', '.']);
                    $files_extensions = ['.php', '.jpg', '.html', '.xml', '.js', '.css', '.scss', '.jxs', '.blade'];
                    foreach ($blocks as $block_slug) {
                        $is_block = true;
                        foreach ($files_extensions as $extension){
                            if (strpos($block_slug, $extension)){
                                $is_block = false;
                            }
                        }
                        if ($is_block){
                            $blocks_list[] = $block_slug;
                        }
                    }
                }
            }
        }
        return $blocks_list;
    }

    public function get_blocks_templates(){
        $blocks_path = $this->blocks_templates_dir;
        $blocks = array_diff(scandir($blocks_path), ['..', '.']);

        $blocks_list = array();
        foreach ($blocks as $block_slug) {
            $blocks_list[] = $block_slug;
        }
        return $blocks_list;
    }

    public function get_block_details(){
        return 'Block Details: '.$this->block_labels->title.' | class: '.$this->block_labels->php_class.' | slug: '.$this->block_labels->slug.' | css class: '.$this->block_labels->css_class;
    }

    public function get_block_template_slug(){
        $block_template = false;
        if (isset($this->commandArgumentBlock)){
            $block_template = $this->input->getArgument($this->commandArgumentBlock);
            if (strpos($block_template, 'acfgb-') === false){
                $block_template = "acfgb-".$block_template;
            }
        }
        return $block_template;
    }

    public function get_block_prefix(){
        $prefix = false;
        if (isset($this->commandArgumentPrefix)){
            $prefix = $this->input->getArgument($this->commandArgumentPrefix);
        }
        return $prefix;
    }

    public function get_block_labels($name){
        return (object) [
            'name' => $name,
            'slug' => $this->name_to_slug($name),
            'title' => $this->name_to_title($name),
            'css_class' => $this->name_to_css_class($name),
            'php_class' => $this->name_to_php_class($name),
            'scss_file' => $this->slug_to_css_file(
                $this->name_to_slug($name)
            ),
            'js_file' => $this->slug_to_js_file(
                $this->name_to_slug($name)
            ),
            'template' => $this->get_block_template_slug(),
            'prefix' => $this->get_block_prefix(),
        ];
    }

}
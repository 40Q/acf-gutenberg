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
    protected $plugin_blocks_dir = __DIR__ .'/../resources/blocks/';
    protected $theme_blocks_dir = '';
    protected $block_labels = [];
    protected $target;
    protected $output;
    protected $input;
    protected $fieldManager;

    protected function execute(InputInterface $input, OutputInterface $output){
        $this->output = $output;
        $this->input = $input;
        $this->input = $input;
        $this->fieldManager = $input;
        $this->set_target($this->input);
        $this->set_block_labels($this->input->getArgument($this->commandArgumentName));
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

    public function set_target($input){
        $this->target = $this->get_target($input);
    }

    public function set_block_labels($name){
        $this->block_labels = (object) [
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
        ];
    }


    /*
     *  ---------------------------------------------------------------------------------------------
     *                                          TASKS
     *  ---------------------------------------------------------------------------------------------
     */

    public function create_block(){
        $blocks_dir = $this->get_target_path($this->target);
        $this->import_block_base($blocks_dir);
        $slug = $this->block_labels->slug;
        $this->rename_file(
            $blocks_dir.$slug."/block_base.blade.php",
            $blocks_dir.$slug."/".$slug.".blade.php"
        );
        $this->rename_file(
            $blocks_dir.$slug."/block_base.class.php",
            $blocks_dir.$slug."/".$slug.".class.php"
        );
        $this->rename_file(
            $blocks_dir.$slug."/_block_base.scss",
            $blocks_dir.$slug."/_".$this->block_labels->scss_file.".scss"
        );
        $this->rename_block_php_class(
            $blocks_dir.$slug."/".$slug.".class.php",
            $this->block_labels->php_class,
            $this->block_labels->title
        );
    }

    public function rename_block_php_class($file, $php_class, $title = false){
        $this->edit_file(
            'replace',
            $file, [
                'BlockBaseTitle' => $title,
                'BlockBase'      => $php_class,
                ],
            'rename php class'
        );
        $this->output->writeln("PHP Class was replaced");
        $this->output->writeln("PHP Title was replaced");
    }

    public function edit_file($action, $file, $args, $task = 'undefined'){
        if (file_exists($file)){
            if (is_writable($file)) {
                switch ($action){
                    case 'replace':
                        $file_open = fopen( $file, "r+");
                        $file_content = fread($file_open, filesize($file));
                        $file_content = str_replace("BlockBaseTitle", $title, $file_content);
                        $file_content = str_replace("BlockBase", $php_class, $file_content);
                        fwrite($file_open, $file_content);
                        fclose($file_open);
                        break;
                    default:
                        $this->output->writeln("ERROR!. Invalid action in edit file. Task: {$task}");
                        die();
                        break;
                }
            }else{
                $this->output->writeln("ERROR!. File is not writable: {$file}. Task: {$task}");
                die();
            }
        }else{
            $this->output->writeln("ERROR!. Can not edit file. File not exist: {$file}. Task: {$task}");
            die();
        }

    }

    public function create_block_scss_file($blocks_dir, $slug, $css_class_name){
        $scss_file = $this->slug_to_css_file($slug);
        if (file_exists($blocks_dir.$slug."/_".$scss_file.".scss")){
            $file_name = $blocks_dir.$slug."/_".$scss_file.".scss";
            $block_base = fopen( $file_name, "r");
            $block_base_content = fread($block_base, filesize($file_name));
            fclose($block_base);

            $new_block = $block_base_content;
            $new_block = str_replace(".b-block-base", ".".$css_class_name, $new_block);

            $new_block_file = fopen($file_name, 'w+');
            fwrite($new_block_file, $new_block);
            fclose($new_block_file);
        }
    }

    public function import_block_base($blocks_dir){
        $new_block_dir = $blocks_dir.$this->block_labels->slug;
        exec("cp -r $this->block_base_dir $new_block_dir");
        $response = "New block created in {$this->target} folder";
        $this->output->writeln($response);
    }

    public function import_scss($blocks_dir, $slug, $css_class_name){
        $scss_file = $this->slug_to_css_file($slug);
        $target = $blocks_dir.'../../assets/styles/blocks.scss';
        $new_block_scss_file = $blocks_dir.$slug."/_".$scss_file.".scss";
        if (file_exists($target)){
            if (file_exists($new_block_scss_file)){
                $blocks_scss = fopen( $target, "a+");
                $blocks_scss_content="\n";
                $blocks_scss_content.='@import "/../../acf-gutenberg/blocks/'.$slug."/".$scss_file.'";';
                fwrite($blocks_scss, $blocks_scss_content);
                fclose($blocks_scss);

                $response = 'Blocks scss imported in Blocks.scss';
            }else{
                $response = 'New block scss file not exist in: '.$new_block_scss_file;
            }
        }else{
            $response = 'Blocks scss file not exist in: '.$target;
        }
        return $response;
    }

    public function import_js($blocks_dir, $slug, $class_name){
        $js_file_name = $this->slug_to_js_file($slug);
        $target = $blocks_dir.$slug;
        $new_js_file = $blocks_dir.$slug."/".$js_file_name.".js";
        if (is_dir($target)){
            exec("cp $this->js_base_file $new_js_file");
            $response = 'Js file imported';
            $response.= "\n";
            $response.= "Remember add this file to you JS routes file, like: ";
            $response.= "\n";
            $response.= " -> import {$js_file_name} from '../../acf-gutenberg/blocks/$slug/$js_file_name';";
        }else{
            $response = 'Block dir exist in: '.$target;
        }
        return $response;
    }


    public function rename_dir (){

    }

    public function rename_file ($target, $new_name){
        $action = false;
        if (file_exists($target)){
            rename ($target, $new_name);
            $action = true;
        }
        return $action;
    }


    public function block_exist($slug){
        $block_exist = false;
        if (is_dir("$this->plugin_blocks_dir/$slug") || is_dir("$this->theme_blocks_dir/$slug")) {
            $block_exist = true;
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

    /*
     *  ---------------------------------------------------------------------------------------------
     *                                          GETTER
     *  ---------------------------------------------------------------------------------------------
     */


    public function get_target(InputInterface $input){
        $target = 'theme';
        if ($input->getOption($this->optionTarget) && $input->getOption($this->optionTarget) == 'plugin') {
            $target = 'plugin';
        }
        return $target;
    }

    public function get_target_path($target){
        $path = $this->theme_blocks_dir;
        if ($target == 'plugin') {
            $path = $this->plugin_blocks_dir;
        }
        return $path;
    }


    public function get_blocks($target = false){
        $blocks_paths = [
            'plugin' => $this->plugin_blocks_dir,
            'theme' => $this->theme_blocks_dir,
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
                    foreach ($blocks as $block_slug) {
                        $blocks_list[] = $block_slug;
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


}
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
    protected $plugin_blocks_dir = __DIR__ .'/../resources/blocks/';
    protected $theme_blocks_dir = '';


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

    public function slug_to_css_file($str)
    {
        $str = ucwords(str_replace('-', '_', $str));
        $str = ucwords(str_replace(' ', '_', $str));
        $str = strtolower($str);
        return $str;
    }

    public function name_to_title($str)
    {
        $str = str_replace('_', ' ', $str);
        $str = str_replace('-', ' ', $str);
        $str = ucfirst(strtolower($str));
        return $str;
    }


    public function create_block($blocks_dir, $slug){
        $new_block_dir = $blocks_dir.$slug;
        exec("cp -r $this->block_base_dir $new_block_dir");

        if (file_exists($blocks_dir.$slug."/block_base.blade.php")){
            rename ($blocks_dir.$slug."/block_base.blade.php", $blocks_dir.$slug."/".$slug.".blade.php");
        }
        if (file_exists($blocks_dir.$slug."/block_base.class.php")){
            rename ($blocks_dir.$slug."/block_base.class.php", $blocks_dir.$slug."/".$slug.".class.php");
        }
        if (file_exists($blocks_dir.$slug."/_block_base.scss")){
            $scss_file = $this->slug_to_css_file($slug);
            rename ($blocks_dir.$slug."/_block_base.scss", $blocks_dir.$slug."/_".$scss_file.".scss");
        }
    }

    public function create_block_class_file($blocks_dir, $slug, $class_name, $title){
        if (file_exists($blocks_dir.$slug."/".$slug.".class.php")){
            $file_name = $blocks_dir.$slug."/".$slug.".class.php";
            $block_base = fopen( $file_name, "r");
            $block_base_content = fread($block_base, filesize($file_name));
            fclose($block_base);

            $new_block = $block_base_content;
            $new_block = str_replace("BlockBaseTitle", $title, $new_block);
            $new_block = str_replace("BlockBase", $class_name, $new_block);

            $new_block_file = fopen($file_name, 'w+');
            fwrite($new_block_file, $new_block);
            fclose($new_block_file);
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
}
<?php
namespace Shove\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class ShoveCLI extends Command
{

	/**
	 * @var OutputInterface $_output. Static output objecy
	 *
	 * @since 1.5.0
	 */
	static $_output;

	/**
	 * @var OutputInterface $output. Output object
	 *
	 * @since 1.5.0
	 */
	private $output;

	/**
	 * @var InputInterface $input. Input object
	 *
	 * @since 1.5.0
	 */
	private $input;

	/**
	 * @var array $theme_info. Theme info
	 *
	 * @since 1.5.0
	 */
	private $theme_info;

	/**
	 * @var array $paths. Utility paths for console commands.
	 *
	 * @since 1.5.0
	 */
	private $paths;

	/**
	 * @var array $block_labels.
	 *
	 * @since 1.5.0
	 */
	private $block_labels;

	/**
	 * @var array $messages. Default messages
	 *
	 * @since 1.5.0
	 */
	private $messages;

	/**
	 * @var array $prefixes. Utility prefixes.
	 *
	 * @since 1.5.0
	 */
	private $prefixes;

	/**
	 * @var array $prefixes. Utility prefixes.
	 *
	 * @since 1.5.0
	 */
	private $questionHelper;


	/**
	 * @var integer. Number of total tasks ran.
	 *
	 * @since 1.5.0
	 */
	private $total_tasks;

	/**
	 * @var integer. Number of total tasks ran successfully.
	 *
	 * @since 1.5.0
	 */
	private $success_tasks;

	/**
	 * Parent command constructor.
	 *
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int|void|null
	 *
	 * @since 1.5.0
	 */
    protected function execute( InputInterface $input, OutputInterface $output ) {

    	$this->set_output( $output );
    	$this->set_input( $input );
    	$this->set_theme_info();
    	$this->set_paths();
    	$this->set_default_messages();
    	$this->set_prefixes();
    	$this->set_helpers();
    	$this->set_tasks();

        $this->command_init();
        die();
    }

    protected function command_init(){
        // Use this method in extended classes
    }

    /*
     *  ---------------------------------------------------------------------------------------------
     *                                          SETTER
     *  ---------------------------------------------------------------------------------------------
     */


	/**
	 * Set output object
	 *
	 * @param OutputInterface $output
	 *
	 * @since 1.5.0
	 */
    public function set_output( OutputInterface $output ) {
		self::$_output = $output;
		$this->output  = $output;
	}

	/**
	 * Set input object
	 *
	 * @param InputInterface $input
	 *
	 * @since 1.5.0
	 */
	public function set_input( InputInterface $input ) {
		$this->input = $input;
	}

	/**
	 * Set active theme info
	 *
	 * @since 1.5.0
	 */
    public function set_theme_info(){
		$style_file       = get_template_directory() . '/style.css';
		$file_headers = array(
			'Name'        => 'Theme Name',
			'ThemeURI'    => 'Theme URI',
			'Description' => 'Description',
			'Author'      => 'Author',
			'AuthorURI'   => 'Author URI',
			'Version'     => 'Version',
			'Template'    => 'Template',
			'Status'      => 'Status',
			'Tags'        => 'Tags',
			'TextDomain'  => 'Text Domain',
			'DomainPath'  => 'Domain Path'
		);
		$this->theme_info = FileManager::get_file_data($style_file, $file_headers);
	}


	/**
	 * Set utility paths.
	 *
	 * @since 1.5.0
	 */
	public function set_paths() {

		$plugin_path = str_replace('/cli', '', __DIR__ );
		$theme_path = get_template_directory() ;

		if ( is_dir( $theme_path . '/resources') ) {
			$theme_path = $theme_path . '/resources';
		}

		$this->paths = [
			'templates'  => $this->add_slash( __DIR__. '/templates' ),
			'theme'      => $this->add_slash( $theme_path ),
			'plugin'     => $this->add_slash( $plugin_path ),

			'stubs'            => $this->add_slash(  __DIR__. '/stubs' ),
			'stubs.block'      => $this->add_slash( __DIR__. '/stubs/block' ),
			'stubs.component'  => $this->add_slash( __DIR__. '/stubs/component' ),
			'stubs.module'     => $this->add_slash( __DIR__. '/stubs/module' ),
			'stubs.plugin'     => $this->add_slash( __DIR__. '/stubs/acf-gutenberg' ),
			'stubs.block.scss' => __DIR__. '/stubs/blocks.scss',

			'plugin.blocks'     => $this->add_slash( $plugin_path . '/resources/blocks' ),
			'plugin.components' => $this->add_slash( $plugin_path . '/resources/components' ),
			'plugin.modules'    => false,

			'theme.plugin'     => $this->add_slash( $theme_path . '/acf-gutenberg/' ),
			'theme.config'     => $this->add_slash( $theme_path . '/acf-gutenberg/config' ),
			'theme.blocks'     => $this->add_slash( $theme_path . '/acf-gutenberg/blocks' ),
			'theme.components' => $this->add_slash( $theme_path . '/acf-gutenberg/components' ),
			'theme.modules'    => $this->add_slash( $theme_path . '/acf-gutenberg/modules' ),
			'theme.assets'     => $this->add_slash( $theme_path . '/assets/' ),
		];
	}

	/**
	 * Set default messages.
	 *
	 * @since 1.5.0
	 */
	public function set_default_messages() {
		$this->messages = [
			'tasks_ready'     => "------ All task ready ------",
			'action_canceled' => "Action canceled",
		];
	}

	/**
	 * Set prefixes.
	 *
	 * @since 1.5.0
	 */
	public function set_prefixes() {
		$this->prefixes = [
			'css' => "b-",
		];
	}

	/**
	 * Set helpers.
	 *
	 * @since 1.5.0
	 */
	public function set_helpers() {
		$this->questionHelper = $this->getHelper('question');
	}

	/**
	 * Set tasks counters.
	 *
	 * @since 1.5.0
	 */
	public function set_tasks() {
		$this->total_tasks   = (int) 0;
		$this->success_tasks = (int) 0;
	}





	/*
     *  ---------------------------------------------------------------------------------------------
     *                                          GETTER
     *  ---------------------------------------------------------------------------------------------
     */


	/**
	 * Get output object
	 *
	 * @return OutputInterface
	 *
	 * @since 1.5.0
	 */
	public function output() {
		return $this->output;
	}

	/**
	 * Get input object
	 *
	 * @return InputInterface
	 *
	 * @since 1.5.0
	 */
	public function input() {
		return $this->input;
	}

	/**
	 * Get input option given
	 *
	 * @param string $option. Option slug
	 * @return bool|string|string[]|null
	 *
	 * @since 1.5.0
	 */
	public function option( $option ) {
		return $this->input()->getOption( $option );
	}

	/**
	 * Get paths list
	 *
	 * @return object
	 *
	 * @since 1.5.0
	 */
	public function paths() {
		return (object) $this->paths;
	}

	/**
	 * Get defined path
	 *
	 * @param string $path. Path slug
	 * @return bool|mixed
	 *
	 * @since 1.5.0
	 */
	public function path( $path ) {
		return ( isset( $this->paths[$path] ) ) ? $this->paths[$path] : false;
	}

	/**
	 * Get default messages list
	 *
	 * @return object
	 *
	 * @since 1.5.0
	 */
	public function get_messages() {
		return (object) $this->messages;
	}

	/**
	 * Get defined message
	 *
	 * @param string $message. Message slug
	 * @return bool|mixed
	 *
	 * @since 1.5.0
	 */
	public function get_message( $message ) {
		return ( isset( $this->messages[$message] ) ) ? $this->messages[$message] : false;
	}

	/**
	 * Get prefix list
	 *
	 * @return object
	 *
	 * @since 1.5.0
	 */
	public function prefixes() {
		return (object) $this->prefixes;
	}

	/**
	 * Get defined prefix
	 *
	 * @param string $prefix. Prefix slug
	 * @return bool|mixed
	 *
	 * @since 1.5.0
	 */
	public function prefix( $prefix ) {
		return ( isset( $this->prefixes[$prefix] ) ) ? $this->prefixes[$prefix] : false;
	}

	/**
	 * Get question helper
	 *
	 * @return bool|mixed
	 *
	 * @since 1.5.0
	 */
	public function question() {
		return $this->questionHelper;
	}

	/**
	 * Increment the number of $total_task
	 *
	 * @param boolean $successfully. Specify if the task was successful. Default: True.
	 *
	 * @since 1.5.0
	 */
	public function add_task( $successfully = true ) {
		if ( $successfully ) $this->success_tasks++;

		$this->total_tasks++;
	}

	/**
	 * Print the tasks resume
	 *
	 * @since 1.5.0
	 */
	public function task_resume() {
		if ( $this->success_tasks == $this->total_tasks ){
			ShovePrint::info('All tasks were successfully. Total: ' . $this->total_tasks);
		} else {
			ShovePrint::message("Success tasks: <info>{$this->success_tasks}</info>/{$this->total_tasks}");
		}
	}


	/*
     *  ---------------------------------------------------------------------------------------------
     *                                          UTILITIES
     *  ---------------------------------------------------------------------------------------------
     */

	public function add_slash($path){
		if (substr($path, -1) != "/" ){
			$path.= '/';
		}
		return $path;
	}


	public function ask_yes_no( $message = false ) {
		$message  = ( ! $message ) ? 'Continue with this action? (y/n) ' : $message;
		$question = new ConfirmationQuestion($message, false);
		return  $this->question()->ask($this->input(), $this->output(), $question);
	}

	public function response_yes( $response ) {
		return ( $response == 'y' || $response == "yes") ? true : false;
	}

















	/**
	 *
	 * MOve to block class
	 */

	public function set_block_labels($name){
		$this->block_labels = [
			'name'      => $name,
			'slug'      => FileManager::name_to_slug($name),
			'title'     => fileManager::name_to_title($name),
			'css_class' => fileManager::name_to_css_class($name),
			'php_class' => fileManager::name_to_php_class($name),
			'scss_file' => fileManager::slug_to_css_file(
				fileManager::name_to_slug($name)
			),
			'js_file'   => fileManager::slug_to_js_file(
				fileManager::name_to_slug($name)
			),
			'template'  => $this->get_block_template_slug(),
			'prefix'    => $this->get_block_prefix(),
		];
	}

	public function set_name_by_prefix($block, $prefix){
		$new_block_name = $block;
		if ($prefix){
			$new_block_name = $prefix."-".$block;
		}
		return $new_block_name;
	}

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
	}













	/*
     *  ---------------------------------------------------------------------------------------------
     *                                          TO REFACTOR
     *  ---------------------------------------------------------------------------------------------
     */






    /*
     *  ---------------------------------------------------------------------------------------------
     *                                          TASKS
     *  ---------------------------------------------------------------------------------------------
     */


    public function rename_block_base_php_class($file, $php_class, $title = false){
        $error = FileManager::edit_file(
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
        $active_theme = $this->add_slash($active_theme);
        $bash_dir = $this->add_slash($bash_dir);
        if ($bash_dir != $active_theme){
            $is_active_theme = false;
        }
//        $this->print($active_theme);
//        $this->print($bash_dir);

        return $is_active_theme;
    }





    public function get_current_blocks_dir(){
        $path = getcwd();
        $path = $this->add_slash($path);
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




}

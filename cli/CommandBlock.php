<?php
namespace Shove\CLI;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use ACF_Gutenberg\Includes\Lib;

class CommandBlock extends ShoveCLI
{
    protected $commandName = 'block';
    protected $commandDescription = "Generate new GB block with ACF";

	protected $action = "action";
	protected $actionDescription = "Action for block command";

    protected $name = "name";
    protected $nameDescription = "Name for new block";

    protected $optionJs = "js"; // should be specified like "create {BlockName} --js"
    protected $optionJsDescription = 'If is set, create js route file for the block.';

    protected $optionFields = "fields"; // --fields=name=text,age=number,features=repeater
    protected $optionFieldsDescription = 'Select target to import block. Can be: theme or plugin';

	/**
	 * @var array $fields. Block fields list.
	 *
	 * @since 1.5.0
	 */
    public $fields = [];

	/**
	 * @var array $block_labels.
	 *
	 * @since 1.5.0
	 */
	private $block_labels;

    protected function configure()
    {
        $this->theme_blocks_dir = get_template_directory().'/acf-gutenberg/blocks/';
        $this
            ->setName($this->commandName)
            ->setDescription($this->commandDescription)
			->addArgument(
				$this->action,
				InputArgument::REQUIRED,
				$this->actionDescription
			)
            ->addArgument(
                $this->name,
                InputArgument::OPTIONAL,
                $this->nameDescription
            )
            ->addOption(
                $this->optionJs,
                null,
                InputOption::VALUE_NONE,
                $this->optionJsDescription
            )
            ->addOption(
                $this->optionFields,
                null,
                InputOption::VALUE_OPTIONAL,
                $this->optionFieldsDescription
            )
        ;
    }


	/*
	*  ---------------------------------------------------------------------------------------------
	*                                          ACTIONS
	*  ---------------------------------------------------------------------------------------------
	*/

    public function create () {
		$name   = $this->argument( $this->name );
		$fields = $this->option($this->optionFields);
		$this->set_block_labels( $name );

		if ( $fields ) {
			$this->set_fields( $fields );
		}

		// Set block slug
        $slug = $this->label('slug');

		if ( ! $this->block_exist( $slug ) ) {
			ShovePrint::title('ACFGB Block create tasks');

			$this->task_create_block_dir();
			$this->task_copy_block_files_in_theme();
			$this->task_rename_block_files_in_theme();
			$this->task_rename_block_base_php_class();
			$this->task_rename_block_base_css_class();
			$this->task_import_block_scss();

			ShovePrint::br();
			ShovePrint::message( $this->get_message('tasks_ready') );
			$this->task_resume();

			if ( $this->has_js() ) {
				ShovePrint::br();
				ShovePrint::warning('Remember import the script file in your main js file');
			}
		}else{
			ShovePrint::error('The block already exists');
		}



	}

	public  function list () {
		ShovePrint::info("✓ Block list");

//		$target = false;
//		if ($this->input()->getOption($this->optionTarget)) {
//			$target = $this->input()->getOption($this->optionTarget);
//		}
//		$blocks = $this->get_blocks($target);
//		$i = 0;
//		$text = '';
//		foreach ($blocks as $block){
//			if (strpos($block, 'Blocks in') === false){ $i++; }
//			if ($i > 0){ $text.= "\n"; }
//			if (strpos($block, 'Blocks in') === false){ $text.= "    ".$i.". "; }
//			$text.= $block;
//		}
//		$this->output()->writeln($text);
	}


	public  function clone () {
		ShovePrint::info("✓ Block Cloned");
	}

	public  function rename () {
		ShovePrint::info("✓ Block renamed");

	}

	public  function delete () {
		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion('<comment>!! Are you sure you want to delete the block ?? (y/n) </comment>', false);
		$confirm = $helper->ask($this->input(), $this->output(), $question);

		if ($confirm == 'y' || $confirm == "yes"){
			ShovePrint::info("✓ Block deleted");

			ShovePrint::info($this->get_message('tasks_ready'));
		}else{
			ShovePrint::comment("<comment>Action canceled</comment>. <info>Your block is safe =)</info>");
		}
	}


	public  function import () {
		ShovePrint::info("✓ Block imported");

	}

	public  function clean()
	{
		$helper = $this->getHelper('question');
		$question = new ConfirmationQuestion('<comment>!! Are you sure you want to delete all block files?? (y/n) </comment>', false);
		$confirm = $helper->ask($this->input(), $this->output(), $question);

		if ($confirm == 'y' || $confirm == "yes"){
			ShovePrint::info("✓ Blocks cleaned");

//				ShovePrint::info("------ Init ACFGB Clean ------");
//
//				// Delete block dir in theme
//				$this->delete_block_dir_in_theme();
//
//				// Delete blocks scss file in theme
//				$this->delete_block_scss_file();
//
//				// Delete block cli file in theme
//				$this->delete_block_cli_file();
//
//				//$this->delete_blocks_scss_in_main();
//				ShovePrint::comment(
//					" - IMPORTANT! Remember delete block scss file reference in main.scss",
//					'comment');
//				ShovePrint::comment(" - IMPORTANT! If you are using custom JS, remember delete JS routes in main.js",
//					'comment');

			ShovePrint::info($this->get_message('tasks_ready'));
		}else{
			ShovePrint::comment("<comment>Action canceled</comment>. <info>Your files are safe =)</info>");
		}

	}



	/*
	*  ---------------------------------------------------------------------------------------------
	*                                          TASKS
	*  ---------------------------------------------------------------------------------------------
	*/

	/**
	 * Create block folder in theme directory
	 *
	 * @since 1.5.0
	 */
	public function task_create_block_dir() {
		$destination = $this->path( 'theme.blocks' ) . $this->label('slug');

		ShovePrint::br();
		ShovePrint::subtitle('Start creating folders');

		// Create block directory
		$error = FileManager::create_dir( $destination );
		if ( $error ) {
			ShovePrint::error( $error );
			$this->add_task( false );
		} else {
			$this->add_task();
			ShovePrint::info(' ✓ Created block folder in theme directory: /acf-gutenberg/blocks/');
		}

	}


	/**
	 * Copy block files: blockBase.class.php, blockBase.blade.php, _blockBase.scss, blockBase.js
	 *
	 * @since 1.5.0
	 */
	public function task_copy_block_files_in_theme() {
		$origin      = $this->path('stubs.block' );
		$destination = $this->path( 'theme.blocks' ) . $this->label('slug');

		if ( is_dir( $destination ) ) {

			ShovePrint::br();
			ShovePrint::subtitle('Start importing block files');


			// Import block class file
			if ( $this->has_fields() ) {
				$file = $destination . '/blockBaseFields.class.stub';
				$error = FileManager::copy_file( $origin . 'blockBaseFields.class.stub', $destination );
			} else {
				$file = $destination . '/blockBase.class.stub';
				$error = FileManager::copy_file( $origin . 'blockBase.class.stub', $destination );
			}
			if ($error){
				ShovePrint::error($error);
				$this->add_task( false );
			} else {
				// Check blocks folder
				if ( file_exists( $file ) ) {
					ShovePrint::info(' ✓ Imported block class');
					$this->add_task();
				} else {
					ShovePrint::error(' x Blocks class file is missing');
					$this->add_task( false );
				}
			}

			// Import block view file
			$file = $destination . '/blockBase.blade.php';
			$error = FileManager::copy_file( $origin . 'blockBase.blade.php', $destination );
			if ($error){
				ShovePrint::error($error);
				$this->add_task( false );
			} else {
				// Check blocks folder
				if ( file_exists( $file ) ) {
					ShovePrint::info(' ✓ Imported block view');
					$this->add_task();
				} else {
					ShovePrint::error(' x Blocks view file is missing');
					$this->add_task( false );
				}
			}


			// Import block style file
			$file = $destination . '/_blockBase.scss';
			$error = FileManager::copy_file( $origin . '_blockBase.scss', $destination );
			if ($error){
				ShovePrint::error($error);
				$this->add_task( false );
			} else {
				// Check blocks folder
				if ( file_exists( $file ) ) {
					ShovePrint::info(' ✓ Imported block styles');
					$this->add_task();
				} else {
					ShovePrint::error(' x Blocks style file is missing');
					$this->add_task( false );
				}
			}

			if ( $this->has_js() ) {
				// Import block js file
				$file = $destination . '/blockBase.js';
				$error = FileManager::copy_file( $origin . 'blockBase.js', $destination );
				if ($error){
					ShovePrint::error($error);
					$this->add_task( false );
				} else {
					// Check blocks folder
					if ( file_exists( $file ) ) {
						ShovePrint::info(' ✓ Imported block script');
						$this->add_task();
					} else {
						ShovePrint::error(' x Blocks script file is missing');
						$this->add_task( false );
					}
				}
			}

		} else {
			ShovePrint::error('Block directory doesn`t exists in: ' . $destination);
			$this->add_task( false );
			$this->add_task( false );
			$this->add_task( false );
		}
	}


	/**
	 * Rename block files: blockBase.class.php, blockBase.blade.php, _blockBase.scss, blockBase.js
	 *
	 * @since 1.5.0
	 */
	public function task_rename_block_files_in_theme() {
		$blocks_dir = $this->path( 'theme.blocks' ) . $this->label('slug');
		$blocks_dir = $this->add_slash( $this->path( 'theme.blocks' ) . $this->label('slug') );

		ShovePrint::br();
		ShovePrint::subtitle('Start renaming block files');

		// Rename php class file
		$new_name = "{$this->label('slug')}.class.php";
		if ( $this->has_fields() ) {
			$error = FileManager::rename_file(
				$blocks_dir."/blockBaseFields.class.stub",
				$blocks_dir.$new_name
			);
		} else {
			$error = FileManager::rename_file(
				$blocks_dir."/blockBase.class.stub",
				$blocks_dir.$new_name
			);
		}
		if ($error){
			ShovePrint::error($error);
			$this->add_task( false );
		} else {
			// Check block class file was renamed
			if ( file_exists( $blocks_dir.$new_name ) ) {
				ShovePrint::info(' ✓ Block class renamed to: ' . $new_name );
				$this->add_task();
			} else {
				ShovePrint::error(' x Blocks class was not renamed' );
				ShovePrint::warning('Rename the block class file manually.' );
				$this->add_task( false );
			}
		}

		// Rename blade view file
		$new_name = "{$this->label('slug')}.blade.php";
		$error = FileManager::rename_file(
			$blocks_dir."/blockBase.blade.php",
			$blocks_dir.$new_name
		);
		if ($error){
			ShovePrint::error($error);
			$this->add_task( false );
		} else {
			// Check block view file was renamed
			if ( file_exists( $blocks_dir.$new_name ) ) {
				ShovePrint::info(' ✓ Block view renamed to: ' . $new_name );
				$this->add_task();
			} else {
				ShovePrint::error(' x Blocks view was not renamed' );
				ShovePrint::warning('Rename the block view file manually.' );
				$this->add_task( false );
			}
		}

		// Rename style file
		$new_name = "_{$this->label('slug')}.scss";
		$error = FileManager::rename_file(
			$blocks_dir."/_blockBase.scss",
			$blocks_dir.$new_name
		);
		if ($error){
			ShovePrint::error($error);
			$this->add_task( false );
		} else {
			// Check block view file was renamed
			if ( file_exists( $blocks_dir.$new_name ) ) {
				ShovePrint::info(' ✓ Block style renamed to: ' . $new_name );
				$this->add_task();
			} else {
				ShovePrint::error(' x Blocks style was not renamed' );
				ShovePrint::warning('Rename the block style file manually.' );
				$this->add_task( false );
			}
		}

		if ( $this->has_js() ) {

			// Rename js file
			$new_name = "{$this->label('js_file')}.js";
			$error = FileManager::rename_file(
				$blocks_dir."/blockBase.js",
				$blocks_dir.$new_name
			);
			if ($error){
				ShovePrint::error($error);
				$this->add_task( false );
			} else {
				// Check block script file was renamed
				if ( file_exists( $blocks_dir.$new_name ) ) {
					ShovePrint::info(' ✓ Block script renamed to: ' . $new_name );
					$this->add_task();
				} else {
					ShovePrint::error(' x Blocks script was not renamed' );
					ShovePrint::warning('Rename the block script file manually.' );
					$this->add_task( false );
				}
			}
		}

	}

	public function task_rename_block_base_php_class(){
		$block_dir = $this->path( 'theme.blocks' ) . $this->label('slug');
		$file =  $block_dir . '/'. $this->label('slug') . '.class.php';

		ShovePrint::br();
		ShovePrint::subtitle('Start editing block files');

		$error = FileManager::edit_file(
			'replace',
			$file,
			[
				'{BlockBaseTitle}' => $this->label('title'),
				'{BlockBase}'      => $this->label('php_class'),
				'{Fields}'         => $this->compose_fields(),
			],
			'rename php class'
		);
		if ($error){
			ShovePrint::error($error);
			$this->add_task( false );
		} else {
			ShovePrint::info(" ✓ PHP Class was replaced: {$this->label('php_class')}" );
			ShovePrint::info(" ✓ PHP Title was replaced: {$this->label('title')}" );
			$this->add_task();
		}
	}

	public function task_rename_block_base_css_class() {
		$block_dir = $this->path( 'theme.blocks' ) . $this->label('slug');
		$file =  $block_dir . '/_'. $this->label('slug') . '.scss';

		$error = FileManager::edit_file(
			'replace',
			$file,
			[
				'prefix-'   => $this->label('css_prefix'),
				'blockBase' => $this->label('css_class'),
			],
			'rename css class'
		);
		if ($error){
			ShovePrint::error($error);
			$this->add_task( false );
		} else {
			ShovePrint::info(" ✓ CSS class was replaced: {$this->label('css_class')}" );
			$this->add_task();
		}
	}

	/**
	 * Import block scss in blocks.scss
	 *
	 * @since 1.5.0
	 */
	public function task_import_block_scss() {
		$css_file = $this->path('theme.assets') .'styles/blocks.scss';

		ShovePrint::br();
		ShovePrint::subtitle('Start import block styles');
		// Check if blocks.scss exists
		if ( file_exists( $css_file) ) {
			ShovePrint::info(' ✓ blocks.scss file founded' );

			$error = FileManager::edit_file( 'add_to_bottom', $css_file, [
				'@import "/../../acf-gutenberg/blocks/'.$this->label('slug')."/".$this->label('scss_file').'";'
			],'import block css in blocks.scss' );
			if ($error){
				ShovePrint::error($error);
			} else {
				ShovePrint::info(' ✓ Block styles imported in block.scss');
				$this->add_task();
			}

		} else {
			ShovePrint::error(' x blocks.scss file not founded' );
			ShovePrint::warning('Block styles was not imported. Import the block file into your block.css file manually.' );
			$this->add_task( false );
		}
	}

	/*
	*  ---------------------------------------------------------------------------------------------
	*                                          UTILITIES
	*  ---------------------------------------------------------------------------------------------
	*/

	/**
	 * Set block labels
	 *
	 * @param string $name. Block name
	 *
	 * @since 1.5.0
	 */
	public function set_block_labels( $name ) {
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
			'css_prefix' => Lib\config('blocks.class_prefix'),
		];
	}

	/**
	 * Get defined label
	 *
	 * @param string $label. Label slug
	 * @return bool|mixed
	 *
	 * @since 1.5.0
	 */
	public function label( $label ) {
		return ( isset( $this->block_labels[$label] ) ) ? $this->block_labels[$label] : false;
	}

	/**
	 * Set block fields
	 *
	 * @param string $fields
	 *
	 * @since 1.5.0
	 */
	public function set_fields( $fields ) {
		$fields = explode(',', $fields);
		foreach ( $fields as $field ) {
			if ( $this->valid_field( $field ) ) {
				$field = explode( '=', $field );
				$slug = ( isset( $field[0] ) ) ? $field[0] : false;
				$type = ( isset( $field[1] ) ) ? $field[1] : false;
				$this->fields[$slug] = $type;
			}
		}
	}

	/**
	 * Compose fields
	 *
	 * @param string $fields
	 *
	 * @since 1.5.0
	 */
	public function compose_fields() {
		if ( ! $this->has_fields() ) return false;

		$need_close = ['repeater', 'group'];

		$fields = '$tabs["content"]["fields"]';
		foreach ( $this->fields as $slug => $type ) {
			$fields.= "\n";
			$method = '->add'.ucfirst( $type );
			$fields.= "\t\t\t{$method}('{$slug}')";

			if ( in_array( $type, $need_close ) ) {
				$fields.= "\n";
				$fields.= "\t\t\t\t//Define sub fields here";
				$fields.= "\n";
				$fields.= "\t\t\t->end".ucfirst( $type )."()";
			}

		}
		$fields.= ";";

		return $fields;
	}

	/**
	 * Check if is a valid field
	 *
	 * @param string $field. Field slug
	 *
	 * @return bool
	 *
	 * @since 1.5.0
	 */
	public function valid_field ( $field ) {

		$field_types = [
			'text', 'textarea', 'number', 'range', 'url', 'password',
			'wysiwyg', 'oembed', 'image', 'file', 'gallery',
			'select', 'checkbox', 'radio', 'true_false',
			'link', 'post_object', 'page_link', 'relationship', 'taxonomy', 'user',
			'google_map', 'date_picker', 'date_time_picker', 'time_picker', 'color_picker',
			'message', 'accordion', 'tab_field', 'group', 'repeater', 'flexible_content',
		];

		$field = explode( '=', $field );
		$slug = ( isset( $field[0] ) ) ? $field[0] : false;
		$type = ( isset( $field[1] ) ) ? $field[1] : false;

		if ( ! $slug || ! is_string( $slug ) || ! $type || ! is_string( $type ) ) return false;

		if ( ! in_array( $type, $field_types ) ) return false;

		return true;
	}


	public function block_exist( $slug ){
		return ( is_dir( $this->path( 'theme.blocks' ) . $slug ) ) ? true : false;
	}

	public function has_fields(){
		return ( is_array( $this->fields ) && count( $this->fields ) >= 1 ) ? true : false;
	}

	public function has_js(){
		return ( $this->option($this->optionJs) ) ? true : false;
	}




	/*
     *  ---------------------------------------------------------------------------------------------
     *                                          TO REFACTOR
     *  ---------------------------------------------------------------------------------------------
     */


/*

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

	public function get_block_details(){
		return 'Block Details: '.$this->block_labels->title.' | class: '.$this->block_labels->php_class.' | slug: '.$this->block_labels->slug.' | css class: '.$this->block_labels->css_class;
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



	public function get_block_template_slug(){
		$block_template = false;
		if (isset($this->commandArgumentBlock)){
			$block_template = $this->input()->getArgument($this->commandArgumentBlock);
			if (strpos($block_template, 'acfgb-') === false){
				$block_template = "acfgb-".$block_template;
			}
		}
		return $block_template;
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


	public function delete_block_dir_in_theme(){

		$error = $this->fileManager()->delete_dir(
			$this->theme_plugin_dir,
			"Delete acf-gutenberg folder in theme"
		);

		if ($error){
			ShovePrint::error($error);
		}else{
			ShovePrint::info(
				" ✓ ACFGB folder deleted");
		}
	}

	public function delete_block_scss_file(){
		$error = $this->fileManager()->delete_file(
			get_template_directory(). '/assets/styles/blocks.scss',
			"Delete blocks scss file in theme"
		);

		if ($error){
			ShovePrint::error($error);
		}else{
			ShovePrint::info(" ✓ Blocks.scss file deleted");
		}
	}

	public function delete_block_cli_file(){
		$error = $this->fileManager()->delete_file(
			get_theme_file_path(). '/block',
			"Delete blocks cli file in theme"
		);

		if ($error){
			ShovePrint::error($error);
		}else{
			ShovePrint::info(" ✓ Blocks CLI file deleted");
		}
	}

	public function delete_blocks_scss_in_main(){
		$text= "\n";
		$target = get_template_directory().'/assets/styles/main.scss';
		if (file_exists($target)){
			$main_scss = fopen( $target, "a+");
			$main_scss_content="\n \n";
			$main_scss_content.='@import "blocks";';
			fwrite($main_scss, $main_scss_content);
			fclose($main_scss);

			$text.= 'Blocks styles imported into main.scss';
		}else{
			$text.= 'Mains scss not found in: '.$target.". Import blocks.scss in your main.scss";
		}
		return $text;
	}*/

}

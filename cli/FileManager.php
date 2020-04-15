<?php
namespace Shove\CLI;

class FileManager
{

	static public function copy_file ($base, $target, $rename = false, $task = false){
        $error = false;
        if (file_exists($base)){
            if (is_dir($target)){
				if ( isset( $rename) ) {
                	exec("cp $base $target$rename");
				} else {
                	exec("cp $base $target");
				}
            }else{
                $error = 'ERROR!. Can not copy file. Target not exist: '.$target." .Task: {$task}";
            }
        }else{
            $error = 'ERROR!. Can not copy file. File base not exist: '.$base." .Task: {$task}";
        }
        return $error;

    }

	static public function create_dir ( $destination ){
		$error = false;
		if ( ! is_dir( $destination ) ) {
			exec("mkdir {$destination}");
		} else {
			$error = "Can not create a new directory. Directory exists in: {$destination}";
		}

		return $error;
	}

	static public function copy_dir ($base, $target, $rename = false, $task = false){
        $error = false;
        if ( is_dir($base) ) {

        	if (is_dir($target)){
            	if ( isset( $rename) ) {
            		exec("cp -r $base $target$rename");
				} else {
            		exec("cp -r $base $target");
				}
            }else{
                $error = "ERROR!. Can not copy dir. Target is not dir: {$target} .Task: {$task}";
            }

        } else {
                $error = "ERROR!. Can not copy dir. Base is not dir: {$base} .Task: {$task}";
        }
        return $error;
    }

	static public function rename_file ($target, $new_name, $task = false){
        $error = false;
        if (file_exists($target)){
            rename ($target, $new_name);
        }else{
            $error = "ERROR!. Can not rename file. File not exist: {$target}";
        }
        return $error;
    }
	static public function rename_dir ($target, $new_name, $task = false){

    }

	static public function edit_file($action, $file, $args, $task = 'undefined'){
        $error = false;
        if (file_exists($file)){
            if (is_writable($file)) {
                switch ($action){
                    case 'replace':
                        $file_temp = fopen( $file, "r");
                        $file_content = fread($file_temp, filesize($file));
                        fclose($file_temp);
                        foreach ($args as $search => $replace){
                            $file_content = str_replace($search, $replace, $file_content);
                        }
                        $file_open = fopen( $file, "w+");
                        fwrite($file_open, $file_content);
                        fclose($file_open);
                        break;
                    case 'add_to_bottom':
                        $file_open = fopen( $file, "a+");
                        $new_content = '';
                        foreach ($args as $line){
                            $new_content.="\n";
                            $new_content.=$line;
                        }
                        fwrite($file_open, $new_content);
                        fclose($file_open);
                        break;
                    default:
                        $error = "ERROR!. Invalid action in edit file. Task: {$task}";
                        break;
                }
            }else{
                $error = "ERROR!. File is not writable: {$file}. Task: {$task}";
            }
        }else{
            $error = "ERROR!. Can not edit file. File not exist: {$file}. Task: {$task}";
        }
        return $error;

    }

	static public function delete_file ($target, $task = false){
        $error = false;
        if (file_exists($target)){
            exec("rm $target");
        }else{
            $error = "ERROR!. Can not delete file. Target is not file: {$target} .Task: {$task}";
        }
        return $error;
    }

	static public function delete_dir ($target, $task = false){
        $error = false;
        if (is_dir($target)){
            exec("rm -rf $target");
        }else{
            $error = "ERROR!. Can not delete dir. Target is not dir: {$target} .Task: {$task}";
        }
        return $error;
    }


    static public function get_file_data( $file, $default_headers) {

		$fp = fopen( $file, 'r' );
		$file_data = fread( $fp, 8192 );
		fclose( $fp );
		$file_data = str_replace( "\r", "\n", $file_data );
		$all_headers = $default_headers;

		foreach ( $all_headers as $field => $regex ) {
			if (preg_match( '/^[ \t\/*#@]*' . preg_quote( $regex, '/' ) . ':(.*)$/mi', $file_data, $match )
				&& $match[1])
				$all_headers[ $field ] = trim(preg_replace("/\s*(?:\*\/|\?>).*/", '', $match[1]));
			else
				$all_headers[ $field ] = '';
		}

		return $all_headers;
	}



	static public function name_to_slug($str){
		$str = str_replace('_', '-', $str);
		$str = str_replace(' ', '-', $str);
		$str = strtolower($str);
		return $str;
	}

	static public function name_to_php_class($str)
	{
		$str = ucwords(str_replace('-', ' ', $str));
		$str = ucwords(str_replace('_', ' ', $str));
		return str_replace(' ', '', $str);
	}

	static public function name_to_css_class($str, $prefix = false )
	{
		$str = ucwords(str_replace('_', '-', $str));
		$str = ucwords(str_replace(' ', '-', $str));
		$str = strtolower($str);
		return $prefix.$str;
	}

	static public function name_to_title($str)
	{
		$str = str_replace('_', ' ', $str);
		$str = str_replace('-', ' ', $str);
		$str = ucfirst(strtolower($str));
		return $str;
	}

	static public function slug_to_css_file($str)
	{
		$str = ucwords(str_replace('_', '-', $str));
		$str = ucwords(str_replace(' ', '_', $str));
		$str = strtolower($str);
		if (mb_substr($str,0,1) != '_'){
			$str = "_".$str;
		}
		return $str;
	}

	static public function slug_to_js_file($str)
	{
		$str_temp = explode('-', $str);
		$str = '';
		foreach ($str_temp as $word){
			$str.= ucfirst($word);
		}
		return $str;
	}


	static public function is_dir_empty($dir) {
		if (!is_readable($dir)) return NULL;
		return (count(scandir($dir)) == 2);
	}
}

<?php

abstract class FileManager
{

    public static function copy_file ($base, $target, $rename, $task = false){
        $error = false;
        if (file_exists($base)){
            if (is_dir($target)){
                exec("cp $base $target$rename");
            }else{
                $error = 'ERROR!. Can not copy file. Target not exist: '.$target." .Task: {$task}";
            }
        }else{
            $error = 'ERROR!. Can not copy file. File base not exist: '.$base." .Task: {$task}";
        }
        return $error;

    }

    public static function copy_dir ($base, $target, $rename, $task = false){
        $error = false;
        if (is_dir($base)){
            if (is_dir($target)){
                exec("cp -r $base $target$rename");
            }else{
                $error = "ERROR!. Can not copy dir. Target is not dir: {$target} .Task: {$task}";
            }
        }else{
                $error = "ERROR!. Can not copy dir. Base is not dir: {$base} .Task: {$task}";
        }
        return $error;
    }

    public static function rename_file ($target, $new_name, $task = false){
        $error = false;
        if (file_exists($target)){
            rename ($target, $new_name);
        }else{
            $error = "ERROR!. Can not rename file. File not exist: {$target}";
        }
        return $error;
    }
    public static function rename_dir ($target, $new_name, $task = false){

    }

    public static function edit_file($action, $file, $args, $task = 'undefined'){
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

    public static function delete_file ($target, $task = false){
        $error = false;
        if (file_exists($target)){
            exec("rm $target");
        }else{
            $error = "ERROR!. Can not delete file. Target is not file: {$target} .Task: {$task}";
        }
        return $error;
    }

    public static function delete_dir ($target, $task = false){
        $error = false;
        if (is_dir($target)){
            exec("rm -rf $target");
        }else{
            $error = "ERROR!. Can not delete dir. Target is not dir: {$target} .Task: {$task}";
        }
        return $error;
    }

}

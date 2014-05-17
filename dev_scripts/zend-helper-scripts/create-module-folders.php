<?php
/**
 * filename:   create-module-folders.php
 * 
 * @author      Chukky Nze <chukkynze@gmail.com>
 * @since       12/19/13 2:23 AM
 * 
 * @copyright   Copyright (c) 2013 www.Nomsterz.com
 */

$lineBreak          =   "\n\n";
$applicationFolder  =   "nomsterz";
$moduleName         =   $argv[1];

if(!is_dir("C:/wamp/www/".$applicationFolder."/module/" . $moduleName . "/"))
{
    echo "We are creating the folders for the module " . $moduleName;
    echo $lineBreak;
    chdir("C:/wamp/www/".$applicationFolder."/module");
    echo $lineBreak;
    echo getcwd();
    echo $lineBreak;
    mkdir($moduleName);
    chdir("C:/wamp/www/".$applicationFolder."/module/" . $moduleName);
    mkdir("config");
    mkdir("src");
    mkdir("language");
    mkdir("view");
    mkdir("test");
    chdir("C:/wamp/www/".$applicationFolder."/module/" . $moduleName . "/src");
    mkdir($moduleName);
    chdir("C:/wamp/www/".$applicationFolder."/module/" . $moduleName . "/src/" . $moduleName . "");
    mkdir("Controller");
    mkdir("Form");
    mkdir("Model");
    mkdir("Mapper");
    chdir("C:/wamp/www/".$applicationFolder."/module/" . $moduleName . "/view");
    mkdir(strtolower($moduleName));
    mkdir("error");
    mkdir("layout");
    chdir("C:/wamp/www/".$applicationFolder."/module/" . $moduleName . "/view/" . strtolower($moduleName) . "");
    mkdir(strtolower($moduleName));
    chdir("C:/wamp/www/".$applicationFolder."/module/" . $moduleName . "/test");
    mkdir($moduleName . "Test");
    chdir("C:/wamp/www/".$applicationFolder."/module/" . $moduleName . "/test/" . $moduleName . "Test/");
    mkdir("Controller");
    chdir("C:/wamp/www/".$applicationFolder."/data");
    mkdir($moduleName);
    chdir("C:/wamp/www/".$applicationFolder."/data/" . $moduleName);
    mkdir("logs");
    mkdir("cache");
}
else
{
    echo "Module " . $moduleName . " already exists." . $lineBreak;
}
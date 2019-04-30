<?php
spl_autoload_register(function ($class) {
    // try to find it in include folder with namespace path
    $className = str_replace("\\", DIRECTORY_SEPARATOR, $class);

    $file = ROOT_DIR . '/' . $className . '.php';
    $fileLower = ROOT_DIR . '/' . strtolower($className) . '.php';

    if ( file_exists($file) )
        include_once($file);
    elseif ( file_exists($fileLower) )
        include_once($fileLower);
    else
        error_log("Can't find file ".$file." that contains class ".$class);
});

?>
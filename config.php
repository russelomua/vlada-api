<?php
/**
 * Error reporting
 */
ini_set('display_errors', 0);
ini_set('error_reporting', E_ERROR & ~E_NOTICE );
error_reporting(E_ERROR & ~E_NOTICE);

/**
 *  DB CONFIGURATION 
 */
define("DB_HOST", "localhost");
define("DB_USER", "vlada");
define("DB_PASSWORD", "9hGx1oXwYBxwiUjo");
define("DB_DB", "vlada");

/**
 * Random secret to generate token
 */
define("API_SECRET", "fRyfGwLZKVm3gwXeHfrEqRNM3wxUm24CogXNrtstJUfG4TdwmdX2KfoHZK7ndJWVguJD49C7nvSNrGb7JTOu");

/**
 * Token lifetime in minutes
 */
define("API_TOKEN_LIFETIME", 60);

/**
 * ROOT directory defining
 */
if ( !defined('ROOT_DIR') )
    define ('ROOT_DIR', (defined('__DIR__') ? __DIR__ : dirname(__FILE__)));

?>

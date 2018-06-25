<?php
date_default_timezone_set('UTC');

define('PRODUCTION_ENVIRONMENT', 'false');  // 'true', 'false'


if(PRODUCTION_ENVIRONMENT == 'true')
{
    define('DOMAINURL','http://demo.techleos.com/4digits/');
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'webtre6_td_di');              // PRODUCTION DB USER
    define('DB_PASS', 'Di3276$#');              // PRODUCTION DB PASSWORD
    define('DB_DATABASE', 'webtre6_techleosdemo_designitaliano');          // PRODUCTION DB NAME
    define('DB_PORT', '3306');


}
else
{
    error_reporting(E_ALL);
    ini_set('display_errors', '-1');

    define('DOMAINURL','http://localhost/docs/');
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_DATABASE', '4digits');
    define('DB_PORT', '3306');

}

define('API_BASE_URL',DOMAINURL.'api/1_0/api.php');
define('APP_NAME','4-DIGITS');

/**** DATABASE TABLE NAME CONSTANT DEFINE ***********/
define('TBL_PREFIX','dg_');
define('TBL_APIS', TBL_PREFIX.'apis');


global $db;

require 'MysqliDb.class.php';

$db = new MysqliDb();

?>
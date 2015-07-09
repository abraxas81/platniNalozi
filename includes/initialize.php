<?php

// Define the core paths
// Define them as absolute paths to make sure that require_once works as expected

// DIRECTORY_SEPARATOR is a PHP pre-defined constant
// (\ for Windows, / for Unix)
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null :
	define('SITE_ROOT', DS.'xampp'.DS.'htdocs'.DS.'platninalozi');

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

// load config file first
require_once(LIB_PATH.DS.'config.php');

// load basic functions next so that everything after can use them
require_once(LIB_PATH.DS.'functions.php');

// load core objects
require_once(LIB_PATH.DS.'session.php');

// load database-related classes
require_once(LIB_PATH . DS .'Models'.DS.'database.php');
require_once(LIB_PATH . DS .'Models'.DS.'User.php');
require_once(LIB_PATH . DS .'Models'.DS.'Zadaci.php');
require_once(LIB_PATH . DS .'Models'.DS.'ZadatakKorisnik.php');
require_once(LIB_PATH . DS .'Models'.DS.'Komentar.php');
require_once(LIB_PATH . DS .'Models'.DS.'Racun.php');
require_once(LIB_PATH . DS .'Models'.DS.'Predlozak.php');
require_once(LIB_PATH . DS .'Models'.DS.'ModelPlacanja.php');
require_once(LIB_PATH . DS .'Models'.DS.'SifraNamjene.php');
require_once(LIB_PATH . DS .'Models'.DS.'Placanje.php');
require_once(LIB_PATH . DS .'Models'.DS.'Valuta.php');
require_once(LIB_PATH . DS .'Models'.DS.'ZbrojniNalog.php');


?>
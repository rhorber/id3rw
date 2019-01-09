<?php

/**
 * Bootstrap file for PHPUnit tests.
 *
 * @package Rhorber\ID3rw\Tests
 * @author  Raphael Horber
 * @version 09.01.2019
 */
namespace Rhorber\ID3rw\Tests;


/** *** Composer autoloader *** */
require_once __DIR__.'/../vendor/autoload.php';


// *** Set tag parsers ***
$GLOBALS['TAG_PARSER_VERSION_3'] = new \Rhorber\ID3rw\TagParser\Version3();
$GLOBALS['TAG_PARSER_VERSION_4'] = new \Rhorber\ID3rw\TagParser\Version4();

<?php

require 'mvc/Loader.php';
require 'mvc_customize/vendor/autoload.php';

$loader = new Loader();
$loader->regDirectory(dirname(__FILE__).'/mvc');
$loader->regDirectory(dirname(__FILE__).'/mvc_customize/controllers');
$loader->regDirectory(dirname(__FILE__).'/mvc_customize/models');
$loader->regDirectory(dirname(__FILE__).'/models');
$loader->register();

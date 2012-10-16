<?php

/*
 * This file is part of the CSMenuBundle package.
 *
 * (c) Pierre du Plessis <info@customscripts.co.za> / CustomScripts
 *
 * @author Pierre du Plessis <info@customscripts.co.za>
 * @link(https://www.github.com/CustomScripts/MenuBundle)
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


use Doctrine\Common\Annotations\AnnotationRegistry;

// Composer
if (file_exists(__DIR__.'/../vendor/autoload.php')) {
	$loader = require_once __DIR__.'/../vendor/autoload.php';

	AnnotationRegistry::registerLoader('class_exists');

	return $loader;
}

throw new \RuntimeException('Could not find vendor/autoload.php, make sure you ran composer.');
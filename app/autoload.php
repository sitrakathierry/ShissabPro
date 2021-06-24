<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/** @var ClassLoader $loader */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader([$loader, 'loadClass']);

// $loader->add('Html2Pdf_', __DIR__.'/../vendor/spipu/html2pdf/src'); //ligne à ajouter


return $loader;

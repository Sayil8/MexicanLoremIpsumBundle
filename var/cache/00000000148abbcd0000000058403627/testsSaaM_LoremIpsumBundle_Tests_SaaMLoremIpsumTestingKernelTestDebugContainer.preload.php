<?php

// This file has been auto-generated by the Symfony Dependency Injection Component
// You can reference it in the "opcache.preload" php.ini setting on PHP >= 7.4 when preloading is desired

use Symfony\Component\DependencyInjection\Dumper\Preloader;

require dirname(__DIR__, 3).'\\vendor/autoload.php';
require __DIR__.'/ContainerRIhy21x/testsSaaM_LoremIpsumBundle_Tests_SaaMLoremIpsumTestingKernelTestDebugContainer.php';

$classes = [];
$classes[] = 'SaaM\LoremIpsumBundle\SaaMLoremIpsumBundle';
$classes[] = 'SaaM\LoremIpsumBundle\Controller\IpsumApiController';
$classes[] = 'Symfony\Component\DependencyInjection\ServiceLocator';
$classes[] = 'SaaM\LoremIpsumBundle\SaaMIpsum';
$classes[] = 'SaaM\LoremIpsumBundle\SaaMWordProvider';
$classes[] = 'Symfony\Component\DependencyInjection\ContainerInterface';

Preloader::preload($classes);

<?php

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.
// Returns the public 'saam_lorem_ipsum.ipsum_api_controller' shared service.

return $this->services['saam_lorem_ipsum.ipsum_api_controller'] = new \SaaM\LoremIpsumBundle\Controller\IpsumApiController(($this->services['saam_lorem_ipsum.saam_ipsum'] ?? $this->load('getSaamLoremIpsum_SaamIpsumService.php')));

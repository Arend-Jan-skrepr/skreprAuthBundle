<?php

namespace Skrepr\SkreprAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class SkreprAuthExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->addAnnotatedClassesToCompile([
            '**Bundle\\Controller\\'
        ]);
    }
}


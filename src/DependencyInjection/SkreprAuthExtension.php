<?php

namespace Skrepr\SkreprAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class SkreprAuthExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = $this->getConfiguration($configs, $container);

        return $configuration;
    }

    public function getAlias()
    {
        return 'skrepr_auth';
    }
}
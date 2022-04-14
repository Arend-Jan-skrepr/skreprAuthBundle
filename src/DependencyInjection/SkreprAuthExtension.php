<?php

namespace Skrepr\SkreprAuthBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Extension\Extension;

class SkreprAuthExtension extends Extension
{
    public function getAlias()
    {
        return 'skrepr_auth';
    }
}
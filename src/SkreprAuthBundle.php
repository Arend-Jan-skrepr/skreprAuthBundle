<?php

namespace Skrepr\SkreprAuthBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SkreprAuthBundle extends Bundle
{
    public function getPath(): string{
        return \dirname(__DIR__);
    }
}
<?php

namespace Skrepr\SkreprAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route(path: '/api/login', name: 'bundle_index')]
    public function handleRequest()
    {

    }
}
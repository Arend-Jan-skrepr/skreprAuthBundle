<?php

namespace Skrepr\SkreprAuthBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiLoginController extends AbstractController
{
    #[Route(path: '/login', name: 'login-index')]
    public function handleRequest(): Response
    {
        return new Response('Hello world!');
    }
}

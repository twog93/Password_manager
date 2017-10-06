<?php

namespace PasswordManager\Bundle\PlatformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PasswordManagerPlatformBundle:Default:index.html.twig');
    }
}

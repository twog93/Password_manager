<?php

namespace PasswordManager\Bundle\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('PasswordManagerCoreBundle:Default:index.html.twig');
    }
}

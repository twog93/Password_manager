<?php
/**
 * Created by PhpStorm.
 * User: TwoG
 * Date: 23/09/2017
 * Time: 10:50
 */

namespace PasswordManager\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;


class SecurityController extends BaseController
{


    public function loginAction(Request $request) {

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            // Sinon redirection index
            return $this->redirectToRoute('password_manager_core_home');
        }
        $response = parent::loginAction($request);
        return $response;

    }
}

<?php
/**
 * Created by PhpStorm.
 * User: duveau
 * Date: 26/02/18
 * Time: 11:32
 */

namespace PasswordManager\Bundle\CoreBundle\UserCondition;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
class PasswordManagerUserCondition
{
    protected $securityContext;

    public function __construct(TokenStorage $securityContext)
    {
        $this->securityContext = $securityContext;
    }
    public function getSecurityContext(){

        return  $this->securityContext;


    }


    public function getUser(){

        return $this->getSecurityContext()->getToken();
    }

    public function getRolesAdmin(){
        $listRolesOfUser ="";
        $userRolesAdmin = $this->getUser()->getRoles();


        foreach ($userRolesAdmin as $value) {
            if($value->getRole() == "ROLE_ADMIN"){

               return true;
            }

        }

    }

}
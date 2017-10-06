<?php

namespace PasswordManager\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PasswordManagerUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

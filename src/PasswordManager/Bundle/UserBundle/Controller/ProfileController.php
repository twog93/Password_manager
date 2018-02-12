<?php
/**
 * Created by PhpStorm.
 * User: TwoG
 * Date: 23/09/2017
 * Time: 10:50
 */

namespace PasswordManager\Bundle\UserBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Controller\ProfileController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use PasswordManager\Bundle\PlatformBundle\Entity\AdvertRepository;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ProfileController extends BaseController
{






    public function showAction()
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $usergroup =  $user->getGroups()->getValues();


    if (!is_object($user) || !$user instanceof UserInterface) {
        throw new AccessDeniedException('This user does not have access to this section.');
    }

    /*****************************************************
     * Add new functionality (e.g. log the profile) *
     *****************************************************/

    $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);

    return $this->render('@FOSUser/Profile/show.html.twig', array(
        'user' => $user,
        'listAdverts' => $listAdverts,
        'userGroup' =>$usergroup,

    ));
    }
}



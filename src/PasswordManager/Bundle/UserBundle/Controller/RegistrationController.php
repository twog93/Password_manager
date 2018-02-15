<?php
/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * fi
 * le that was distributed with this source code.
 */


// Overriding FOSUSER registration controller.

namespace PasswordManager\Bundle\UserBundle\Controller;

use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\RegistrationController as BaseController;

/**
 * Controller managing the registration.
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Christophe Coevoet <stof@notk.org>
 */
class RegistrationController extends BaseController

 {
          public function registerAction(Request $request)
          {
              /** @var $formFactory FactoryInterface */
              $formFactory = $this->get('fos_user.registration.form.factory');
              /** @var $userManager UserManagerInterface */
              $userManager = $this->get('fos_user.user_manager');
              /** @var $dispatcher EventDispatcherInterface */
              $dispatcher = $this->get('event_dispatcher');

              $user = $userManager->createUser();
              $user->setEnabled(true);
              $user = $userManager->createUser();
              $user->setEnabled(true);

              //Set default user groups on registration
              $groupName = 'Sans groupe';
              $em = $this->getDoctrine()->getManager();
              $group = $em->getRepository('PasswordManagerUserBundle:Group')->findOneByName($groupName);
              $user->addGroup($group);


              $event = new GetResponseUserEvent($user, $request);
              $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);
              if (null !== $event->getResponse()) {
                  return $event->getResponse();
              }

              $form = $formFactory->createForm();
              $form->setData($user);

              $form->handleRequest($request);

              if ($form->isSubmitted()) {
                  if ($form->isValid()) {
                      $event = new FormEvent($form, $request);
                      $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

                      $userManager->updateUser($user);

                      /*****************************************************
                       * Add new functionality (e.g. log the registration) *
                       *****************************************************/
                      $this->container->get('logger')->info(
                          sprintf("New user registration: %s", $user)
                      );

                      if (null === $response = $event->getResponse()) {
                          $url = $this->generateUrl('fos_user_registration_confirmed');
                          $response = new RedirectResponse($url);
                      }

                      $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

                      return $response;
                  }

                  $event = new FormEvent($form, $request);
                  $dispatcher->dispatch(FOSUserEvents::REGISTRATION_FAILURE, $event);

                  if (null !== $response = $event->getResponse()) {
                      return $response;
                  }
              }

              return $this->render('@FOSUser/Registration/register.html.twig', array(
                  'form' => $form->createView(),
              ));
          }
     }
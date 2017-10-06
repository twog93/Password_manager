<?php

namespace PasswordManager\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PasswordManager\Bundle\CoreBundle\Entity\Contact;
use PasswordManager\Bundle\CoreBundle\Form\ContactType;

// Class HomeController


class HomeController extends Controller
{
    public function indexAction()
    {
        // Vérification si anonyme ou authentifier
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            //Display home Page
            $userId = $this->getUser()->getId();
            $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);

            return $this->render('PasswordManagerCoreBundle:Home:index.html.twig', array(

                'listAdverts' => $listAdverts,));

        }
		// renvois Login Page

        return $this->redirectToRoute('fos_user_security_login');

    }
	
	 public function contactAction(Request $request)
    {
        // Vérification si anonyme ou authentifier
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            // Sinon redirection login page
            return $this->redirectToRoute('password_manager_core');
        }

        $user = $this->getUser();
        $contact = new Contact();

        // Ajout du formulaire de contact
        $form = $this->get('form.factory')->create(ContactType::class, $contact);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $contact->setUser($user);
            $em->persist($contact);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'message bien enregistrée.');
            return $this->redirectToRoute('password_manager_core_home');

        }



        // On passe la méthode createView() du formulaire à la vue

        return $this->render('PasswordManagerCoreBundle:Home:contact.html.twig', array(

            'form' => $form->createView(),));

  }

    public function checkGetPass(){

        $userId = $this->getUser()->getId();
        $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);
        return $listAdverts;

    }

    public function generatorAction()
    {
        // Vérification si anonyme ou authentifier
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            // Sinon redirection login page
            return $this->redirectToRoute('password_manager_core');
        }

        $user = $this->getUser();

        return $this->render('PasswordManagerCoreBundle:Home:generate_password.html.twig');



    }
}

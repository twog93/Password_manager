<?php

namespace PasswordManager\Bundle\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PasswordManager\Bundle\CoreBundle\Entity\Contact;
use PasswordManager\Bundle\CoreBundle\Form\ContactType;

// Class HomeController


class HomeController extends Controller
{

    // Master page
    public function indexAction()
    {

        //Get User Roles form service
        $roles = $this->container->get('password_manager_core.UserCondition');
        $roles = $roles->getRolesAdmin();

        // Vérification si anonyme ou authentifier
        if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            //Display home Page
            $userId = $this->getUser()->getId();
            $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);

            return $this->render('PasswordManagerCoreBundle:Home:index.html.twig', array(

                'roles' => $roles,
                'listAdverts' => $listAdverts,
                'currentUser' => $this->getUser()->getUsername()));

        }
		// renvois Login Page

        return $this->redirectToRoute('fos_user_security_login');
    }

    //Contact Page
	 public function contactAction(Request $request)
    {

        // Vérification si anonyme ou authentifier
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            // Sinon redirection login page
            return $this->redirectToRoute('fos_user_security_login');
        }



        //Add Contact entity by form
        $user = $this->getUser();
        $contact = new Contact();
        $form = $this->get('form.factory')->create(ContactType::class, $contact);
        //Get User Roles form service
        $roles = $this->container->get('password_manager_core.UserCondition');
        $roles = $roles->getRolesAdmin();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $useremail = $this->getUser()->getEmail();
            $mailer = $this->container->get('mailer');
            $userId = $this->getUser()->getId();
            $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);

            //Send email User
            $message = (new \Swift_Message('Confirmation de réception de votre message'))
                ->setFrom('contact-gmp@afbiodiversite.fr')
                ->setTo($useremail)
                ->setBody(
                    $this->renderView(
                        'PasswordManagerCoreBundle:Home:mailing-contact.html.twig',
                        array('subject' => $contact->getSubject(),
                            'author' => $user->getUsername(),
                            'motive' => $contact->getCategorieContact()->getMotive(),
                            'body' => $contact->getBody())
                    ),
                    'text/html'
                );

            //Send email to admin
            $messageToAdmin = (new \Swift_Message("Demande d'information"))
                ->setFrom($useremail)
                ->setTo('contact-gmp@afbiodiversite.Fr')
                ->setBody(
                    $this->renderView(
                        'PasswordManagerCoreBundle:Home:mailing-contact-admin.html.twig',
                        array('subject' => $contact->getSubject(),
                            'author' => $user->getUsername(),
                            'motive' => $contact->getCategorieContact()->getMotive(),
                            'body' => $contact->getBody(),
                            'email' => $user->getEmail())
                    ),
                    'text/html'
                );


            //$test= getMotiveCategorieContact($category);
            //swiftmailer Send Mail
            $mailer->send($message);
            $mailer->send($messageToAdmin);


            // create message in BDD
            $em = $this->getDoctrine()->getManager();
            $contact->setUser($user);
            $em->persist($contact);
            $em->flush();

            //Add flush message
            $request->getSession()->getFlashBag()->add('notice', "Votre message a été envoyé à l'équipe support");
            return $this->redirectToRoute('password_manager_core_home');


        }

        // On passe la méthode createView() du formulaire à la vue
        return $this->render('PasswordManagerCoreBundle:Home:contact.html.twig', array(
            'form' => $form->createView(),
            'roles' => $roles,));
    }

    protected function checkGetPass(){
    //Under construction
        $userId = $this->getUser()->getId();
        $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);
        return $listAdverts;

    }

    // Password generator Page
    public function generatorAction()
    {
        // Vérification si anonyme ou authentifier
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            // Sinon redirection login page
            return $this->redirectToRoute('fos_user_security_login');
        }
        $user = $this->getUser();
        $userId = $user->getId();

        //Get User Roles form service
        $roles = $this->container->get('password_manager_core.UserCondition');
        $roles = $roles->getRolesAdmin();

        $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);


        return $this->render('PasswordManagerCoreBundle:Home:generate_password.html.twig', array(

            'listAdverts' => $listAdverts,
                'roles' => $roles,
                )

        );



    }

    public function ldapAction(){

        $server = "ldap://159.180.228.235";
        $person = "gerald";
        $port = "389";
        $user ="sysldapconnect@afbiodiversite.fr";
        $user2 ="cn=sysldapconnect@afbiodiversite.fr,dc=fr";
        $racine = "dc=afbiodiversite,dc=fr";
        $rootdn = "cn=sysldapconnect@afbiodiversite.fr,dc=afbiodiversite,dc=fr";
        $justthese = array("ou", "sn", "givenname", "mail");
        $filter = "(cn=*)";

        $rootpw = "ID_retr!2017";



        $ldapconn=ldap_connect($server);
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        if($ldapconn) {

            $ldapbind = ldap_bind($ldapconn, $user, $rootpw) or die ("Error trying to bind: ".ldap_error($ldapconn));
            if ($ldapbind) {

                $result = ldap_search($ldapconn, $racine,$filter) or die ("Error in search query: " . ldap_error($ldapconn));
                dump($result);
                $data = ldap_get_entries($ldapconn, $result);
                dump($data);

            }
        }




        return $this->render('PasswordManagerCoreBundle:Home:ldap.html.twig', array(


            "result" =>$result,
            "data" => $data,));

    }


}


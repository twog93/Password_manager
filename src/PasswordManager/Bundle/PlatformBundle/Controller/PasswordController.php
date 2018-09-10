<?php

namespace PasswordManager\Bundle\PlatformBundle\Controller;


use PasswordManager\Bundle\PlatformBundle\Entity\ApplicationRepository;
use PasswordManager\Bundle\PlatformBundle\Entity\PasswordRepository;
use PasswordManager\Bundle\UserBundle\Repository\GroupRepository;
use PasswordManager\Bundle\PlatformBundle\Entity\PasswordSkill;
use PasswordManager\Bundle\PlatformBundle\Entity\Skill;
use PasswordManager\Bundle\PlatformBundle\Entity\Application;
use PasswordManager\Bundle\PlatformBundle\Entity\Password;
use PasswordManager\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PasswordManager\Bundle\PlatformBundle\Form\PasswordEditType;
use PasswordManager\Bundle\PlatformBundle\Form\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;



class PasswordController extends Controller

{

  /*public function listApplicationAction(){

     $em = $this->getDoctrine()->getManager();
     $listPasswords = $em->getRepository('PasswordManagerPlatformBundle:Password')->getPasswordWithCategories(array('Développement web', 'Intégration'));

     return $this->render('PasswordManagerPlatformBundle:Password:index.html.twig', array('listPasswords' => $listPasswords));
  }*/
 /* public function listAnnonceAction(){

     $em = $this->getDoctrine()->getManager();
     $listApplications = $em->getRepository('PasswordManagerPlatformBundle:Application')->getApplicationsWithPassword(2);

     return $this->render('PasswordManagerPlatformBundle:Password:application.html.twig', array('listApplications' => $listApplications ));
  }*/
  public function viewAction($id){

      //Get User Roles form service
      $roles = $this->container->get('password_manager_coreœ.UserCondition');
      $roles = $roles->getRolesAdmin();

      //Get User ID
      $userId = $this->getUser()->getId();
      $listPasswords = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Password')->myFindUserId($userId);
      //If pass exist
      $em = $this->getDoctrine()->getManager();
      $password = $em->getRepository('PasswordManagerPlatformBundle:Password')->find($id);

      if (null === $password) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

        $listApplication = $em->getRepository('PasswordManagerPlatformBundle:Application')->findBy(array('password' => $password));
        $listPasswordSkills = $em->getRepository('PasswordManagerPlatformBundle:PasswordSkill')->findBy(array('password' => $password));
        $listCategories = $em->getRepository('PasswordManagerPlatformBundle:Password')->find($id);
	
    return $this->render('PasswordManagerPlatformBundle:Password:view.html.twig', array(
		'password' => $password,
        'roles' => $roles,
        'listApplication' => $listApplication,
        'listPasswordSkills' => $listPasswordSkills,
        'listCategories' => $listCategories,
        'listPasswords' => $listPasswords
		));
  }
  public function viewSlugAction($year, $slug, $format){

    return new Response("Affichage de l'annonce d'id : ".$year . $slug . $format);
  }
  public function indexAction($category){

      // Check if user
      if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {


          //Charge list of category
          $listCategory = [];
          array_push($listCategory , $category);
          //Get current user

          $user = $this->getUser();
          $userId = $user->getId();

          //Get list of user groups
          $userGroup =  $user->getGroups()->getValues();

          $listGroupOfUser = array();
          foreach ($userGroup as $value) {
            array_push($listGroupOfUser, $value->getName());
          }

        //Get User Roles form service
          $roles = $this->container->get('password_manager_core.UserCondition');
          $roles->getRolesAdmin();


          //get password list

            //Display list of password , sorted by categories and create by current user.
            if($listCategory[0] != "all" && $listCategory[0] != "shared"){
                $listPasswords = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Password')->getPasswordWithCategoriesByAuthor($userId, $listCategory);
               // $listPassShared = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Password')->getPasswordWithCategoriesShared($listCategory);
                //$merge = array_merge($listPasswords, $listPassShared);


                if (!$listPasswords) {

                    throw new \Exception("Vous n'avez pas de contenu dans cette catégorie");

                }

                return $this->render('PasswordManagerPlatformBundle:Password:list-all.html.twig', array(

                    'listPasswords' => $listPasswords,
                    'roles'       =>$roles,
                ));
            }
            elseif($listCategory[0] == "shared"){

                $listPassSharedGrouped = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Password')->getPasswordWithGroupByAuthor($listGroupOfUser);
                return $this->render('PasswordManagerPlatformBundle:Password:list-all.html.twig', array(

                    'listPasswords' => $listPassSharedGrouped,
                    'roles'       =>$roles,
                ));
            }
            else{
                //$listPassShared = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Password')->getPasswordShared();
                $listPassOfUser = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Password')->myFindUserId($userId);

                //$merge = array_merge($listPassOfUser, $listPassShared);

                return $this->render('PasswordManagerPlatformBundle:Password:list-all.html.twig', array(

                    'listPasswords' => $listPassOfUser,
                    'roles'       =>$roles,
                ));

            }



          }

      return $this->redirectToRoute('fos_user_security_login');
  }
  public function addAction(Request $request){



      if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
          // Sinon redirection login page
          return $this->redirectToRoute('fos_user_security_login');
      }
      //Get User Roles form service
      $roles = $this->container->get('password_manager_core.UserCondition');
      $roles = $roles->getRolesAdmin();

      $userId = $this->getUser()->getId();
      $listPasswords = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Password')->myFindUserId($userId);

      $user = $this->getUser();

        $password = new Password();
      $class_methods = get_class_methods('Password');

    // On ajoute le formulaire créer avec doctrine et la class PasswordType
      $form = $this->get('form.factory')->create(PasswordType::class, $password, array('currentUser' => $user));

     if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


              $em = $this->getDoctrine()->getManager();
              $password->setUser($user);
              $em->persist($password);
              $em->flush();

              $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
              return $this->redirectToRoute('password_manager_platform_view', array('id' => $password->getId()));

    }


    // On passe la méthode createView() du formulaire à la vue

    return $this->render('PasswordManagerPlatformBundle:Password:add.html.twig', array(

      'form' => $form->createView(),
        'listPasswords' => $listPasswords,
        'roles' => $roles));

	}
  public function editAction($id, Request $request){

      if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

          //Get User Roles form service
          $roles = $this->container->get('password_manager_core.UserCondition');
          $roles = $roles->getRolesAdmin();


     $em = $this->getDoctrine()->getManager();
     $password = $em->getRepository('PasswordManagerPlatformBundle:Password')->find($id);
     $user = $this->getUser();
      //If user got 1 pass
      $userId = $this->getUser()->getId();
      $listPasswords = $em->getRepository('PasswordManagerPlatformBundle:Password')->myFindUserId($userId);

      if(null === $password){

        throw new NotFoundHttpException("Le mot de passe d'id ".$id." n'existe pas.");
      }
      $userId = $this->getUser()->getId();
      $listPasswords = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Password')->myFindUserId($userId);
      $form = $this->get('form.factory')->create(PasswordEditType::class, $password, array('currentUser' => $user));


      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

          $em->persist($password);
          $em->flush();

          $request->getSession()->getFlashBag()->add('notice', 'Mot de passe bien modifié.');


          return  $this->redirectToRoute('password_manager_platform_view', array('id' => $password->getId()));

      }

      return $this->render('PasswordManagerPlatformBundle:Password:edit.html.twig', array(
          'roles'=> $roles,
          'form' => $form->createView(),
          'listPasswords' => $listPasswords
      ));

      }
      return $this->redirectToRoute('fos_user_security_login');
  }
  public function deleteAction($id, Request $request){

      if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

          //Get User Roles form service
          $roles = $this->container->get('password_manager_core.UserCondition');
          $roles = $roles->getRolesAdmin();

      $em = $this->getDoctrine()->getManager();
      $password = $em->getRepository('PasswordManagerPlatformBundle:Password')->find($id);
      $userId = $this->getUser()->getId();
      $listPasswords = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Password')->myFindUserId($userId);

      if (null === $password) {

        throw new NotFoundHttpException("Le mot de passe d'id ".$id." n'existe pas.");
      }

      $form = $this->get('form.factory')->create();


      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($password);
          $em->flush();

          $request->getSession()->getFlashBag()->add('notice', "Le mot de passe à été supprimé.");

          return $this->redirectToRoute('password_manager_core_home');

      }
      return $this->render('PasswordManagerPlatformBundle:Password:delete.html.twig',
          array(
              'password' => $password,
              'roles'  => $roles,
              'form'   => $form->createView(),
              'listPasswords' => $listPasswords));
      }
      return $this->redirectToRoute('fos_user_security_login');
  }

}

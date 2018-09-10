<?php

namespace PasswordManager\Bundle\PlatformBundle\Controller;


use PasswordManager\Bundle\PlatformBundle\Entity\ApplicationRepository;
use PasswordManager\Bundle\PlatformBundle\Entity\AdvertRepository;
use PasswordManager\Bundle\UserBundle\Repository\GroupRepository;
use PasswordManager\Bundle\PlatformBundle\Entity\AdvertSkill;
use PasswordManager\Bundle\PlatformBundle\Entity\Skill;
use PasswordManager\Bundle\PlatformBundle\Entity\Application;
use PasswordManager\Bundle\PlatformBundle\Entity\Advert;
use PasswordManager\Bundle\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use PasswordManager\Bundle\PlatformBundle\Form\AdvertEditType;
use PasswordManager\Bundle\PlatformBundle\Form\AdvertType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;



class AdvertController extends Controller

{

  /*public function listApplicationAction(){

     $em = $this->getDoctrine()->getManager();
     $listAdverts = $em->getRepository('PasswordManagerPlatformBundle:Advert')->getAdvertWithCategories(array('Développement web', 'Intégration'));

     return $this->render('PasswordManagerPlatformBundle:Advert:index.html.twig', array('listAdverts' => $listAdverts));
  }*/
 /* public function listAnnonceAction(){

     $em = $this->getDoctrine()->getManager();
     $listApplications = $em->getRepository('PasswordManagerPlatformBundle:Application')->getApplicationsWithAdvert(2);

     return $this->render('PasswordManagerPlatformBundle:Advert:application.html.twig', array('listApplications' => $listApplications ));
  }*/
  public function viewAction($id){

      //Get User Roles form service
      $roles = $this->container->get('password_manager_core.UserCondition');
      $roles = $roles->getRolesAdmin();

      //Get User ID
      $userId = $this->getUser()->getId();
      $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);
      //If pass exist
      $em = $this->getDoctrine()->getManager();
      $advert = $em->getRepository('PasswordManagerPlatformBundle:Advert')->find($id);

      if (null === $advert) {
        throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
      }

        $listApplication = $em->getRepository('PasswordManagerPlatformBundle:Application')->findBy(array('advert' => $advert));
        $listAdvertSkills = $em->getRepository('PasswordManagerPlatformBundle:AdvertSkill')->findBy(array('advert' => $advert));
        $listCategories = $em->getRepository('PasswordManagerPlatformBundle:Advert')->find($id);
	
    return $this->render('PasswordManagerPlatformBundle:Advert:view.html.twig', array(
		'advert' => $advert,
        'roles' => $roles,
        'listApplication' => $listApplication,
        'listAdvertSkills' => $listAdvertSkills,
        'listCategories' => $listCategories,
        'listAdverts' => $listAdverts
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
                $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->getAdvertWithCategoriesByAuthor($userId, $listCategory);
               // $listPassShared = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->getAdvertWithCategoriesShared($listCategory);
                //$merge = array_merge($listAdverts, $listPassShared);


                if (!$listAdverts) {

                    throw new \Exception("Vous n'avez pas de contenu dans cette catégorie");

                }

                return $this->render('PasswordManagerPlatformBundle:Advert:list-all.html.twig', array(

                    'listAdverts' => $listAdverts,
                    'roles'       =>$roles,
                ));
            }
            elseif($listCategory[0] == "shared"){

                $listPassSharedGrouped = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->getAdvertWithGroupByAuthor($listGroupOfUser);
                return $this->render('PasswordManagerPlatformBundle:Advert:list-all.html.twig', array(

                    'listAdverts' => $listPassSharedGrouped,
                    'roles'       =>$roles,
                ));
            }
            else{
                //$listPassShared = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->getAdvertShared();
                $listPassOfUser = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);

                //$merge = array_merge($listPassOfUser, $listPassShared);

                return $this->render('PasswordManagerPlatformBundle:Advert:list-all.html.twig', array(

                    'listAdverts' => $listPassOfUser,
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
      $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);

      $user = $this->getUser();

        $advert = new Advert();
      $class_methods = get_class_methods('Advert');

    // On ajoute le formulaire créer avec doctrine et la class AdvertType
      $form = $this->get('form.factory')->create(AdvertType::class, $advert, array('currentUser' => $user));

     if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


              $em = $this->getDoctrine()->getManager();
              $advert->setUser($user);
              $em->persist($advert);
              $em->flush();

              $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
              return $this->redirectToRoute('password_manager_platform_view', array('id' => $advert->getId()));

    }


    // On passe la méthode createView() du formulaire à la vue

    return $this->render('PasswordManagerPlatformBundle:Advert:add.html.twig', array(

      'form' => $form->createView(),
        'listAdverts' => $listAdverts,
        'roles' => $roles));

	}
  public function editAction($id, Request $request){

      if ($this->get('security.authorization_checker')->isGranted('ROLE_USER')) {

          //Get User Roles form service
          $roles = $this->container->get('password_manager_core.UserCondition');
          $roles = $roles->getRolesAdmin();


     $em = $this->getDoctrine()->getManager();
     $advert = $em->getRepository('PasswordManagerPlatformBundle:Advert')->find($id);
     $user = $this->getUser();
      //If user got 1 pass
      $userId = $this->getUser()->getId();
      $listAdverts = $em->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);

      if(null === $advert){

        throw new NotFoundHttpException("Le mot de passe d'id ".$id." n'existe pas.");
      }
      $userId = $this->getUser()->getId();
      $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);
      $form = $this->get('form.factory')->create(AdvertEditType::class, $advert, array('currentUser' => $user));


      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

          $em->persist($advert);
          $em->flush();

          $request->getSession()->getFlashBag()->add('notice', 'Mot de passe bien modifié.');


          return  $this->redirectToRoute('password_manager_platform_view', array('id' => $advert->getId()));

      }

      return $this->render('PasswordManagerPlatformBundle:Advert:edit.html.twig', array(
          'roles'=> $roles,
          'form' => $form->createView(),
          'listAdverts' => $listAdverts
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
      $advert = $em->getRepository('PasswordManagerPlatformBundle:Advert')->find($id);
      $userId = $this->getUser()->getId();
      $listAdverts = $this->getDoctrine()->getManager()->getRepository('PasswordManagerPlatformBundle:Advert')->myFindUserId($userId);

      if (null === $advert) {

        throw new NotFoundHttpException("Le mot de passe d'id ".$id." n'existe pas.");
      }

      $form = $this->get('form.factory')->create();


      if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
          $em->remove($advert);
          $em->flush();

          $request->getSession()->getFlashBag()->add('notice', "Le mot de passe à été supprimé.");

          return $this->redirectToRoute('password_manager_core_home');

      }
      return $this->render('PasswordManagerPlatformBundle:Advert:delete.html.twig',
          array(
              'advert' => $advert,
              'roles'  => $roles,
              'form'   => $form->createView(),
              'listAdverts' => $listAdverts));
      }
      return $this->redirectToRoute('fos_user_security_login');
  }

}

<?php


namespace PasswordManager\Bundle\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PasswordManager\Bundle\PlatformBundle\Entity\Password as Password;
use PasswordManager\Bundle\PlatformBundle\Controller\PasswordController;
use PasswordManager\Bundle\CoreBundle\UserCondition\PasswordManagerUserCondition;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use PasswordManager\Bundle\UserBundle\Entity\User;

class LoadPassword implements FixtureInterface
{
 
  public function load(ObjectManager $manager){

      //$roles = $this->container->get('password_manager_core.UserCondition');
      //$roles = $roles->getRolesAdmin();
      $user = $this->getUser();
      $csv = fopen(dirname(__FILE__).'/loadexistPass/file.csv', 'r');
      $i = 0;
      while (!feof($csv)) {
          $line = fgetcsv($csv);;
          $existantPass[$i] = getUser();
          $existantPass[$i] = new Password();
          $existantPass[$i]->setTitle($line[0]);
          $existantPass[$i]->setShared(1);
          $existantPass[$i]->setUrl($line[0]);
          $existantPass[$i]->setLogin($line[1]);
          $existantPass[$i]->setPassword($line[2]);
          $existantPass[$i]->setContent("rest");
          $manager->persist($existantPass[$i]);

          $this->addPassword('existantPass-'.$i, $existantPass[$i]);


          $i = $i + 1;
      }

      fclose($csv);

      $manager->flush();
  }


 
}


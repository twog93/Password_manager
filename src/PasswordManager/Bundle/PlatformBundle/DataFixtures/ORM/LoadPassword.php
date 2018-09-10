<?php


namespace PasswordManager\Bundle\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PasswordManager\Bundle\PlatformBundle\Entity\Advert;
use PasswordManager\Bundle\UserBundle\Entity\User;


class LoadAdvert implements FixtureInterface
{
 
  public function load(ObjectManager $manager){

      $roles = $this->container->get('password_manager_core.UserCondition');
      $roles = $roles->getRolesAdmin();

      $csv = fopen(dirname(__FILE__).'/loadexistPass/file.csv', 'r');
      $i = 0;
      while (!feof($csv)) {
          $line = fgetcsv($csv);
            echo $line[5];
          $existantUser[$i] = $this->user();
          $existantPass[$i] = new Advert();
          $existantPass[$i]->setTitle("fff");
          $existantPass[$i]->setShared(1);
          $existantPass[$i]->setUrl("http://f");
          $existantPass[$i]->setLogin("");
          $existantPass[$i]->setPassword("test");
          $existantPass[$i]->setContent("rest");
          $manager->persist($existantPass[$i]);

          //$this->addAdvert('existantPass-'.$i, $existantPass[$i]);


          $i = $i + 1;
      }

      fclose($csv);

      $manager->flush();
  }


 
}


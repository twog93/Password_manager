<?php


namespace PasswordManager\Bundle\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PasswordManager\Bundle\PlatformBundle\Entity\Password as Password;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use PasswordManager\Bundle\PlatformBundle\Controller\PasswordController;



class LoadPassword extends PasswordController implements FixtureInterface
{





   public function load(ObjectManager $manager){



       $csv = fopen(dirname(__FILE__).'/loadexistPass/file.csv', 'r');
        $i = 0;
        while (!feof($csv)) {

            $line = fgetcsv($csv, "2000", ",");
            $existantPass[$i] = new Password();
            $existantPass[$i]->setShared(1);
            $existantPass[$i]->setTitle($line[0]);
            $existantPass[$i]->setUrl($line[0]);
            $existantPass[$i]->setLogin($line[1]);
            $existantPass[$i]->setPassword($line[2]);
            $existantPass[$i]->setContent('my str');
            $manager->persist($existantPass[$i]);
            //dump($line[0]);
            echo $line[0];
            //$this->addAction('existantPass-'.$i, $existantPass[$i]);


            $i = $i ++;
        }

        fclose($csv);

        $manager->flush();
    }

}


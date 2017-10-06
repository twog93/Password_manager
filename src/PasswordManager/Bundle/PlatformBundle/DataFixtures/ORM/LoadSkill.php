<?php


namespace PasswordManager\Bundle\PlatformBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PasswordManager\Bundle\PlatformBundle\Entity\Skill;


class LoadSkill implements FixtureInterface
{
 
  public function load(ObjectManager $manager){

    $names = array('PHP', 'Symfony2', 'C++', 'Java', 'Photoshop', 'Blender', 'Bloc-note');

        foreach($names as $name){

            $skill = new Skill();
            $skill->setName($name);

             // On la persiste

            $manager->persist($skill);
        }

        $manager->flush();
  }

 
}
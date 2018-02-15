<?php

namespace PasswordManager\Bundle\UserBundle\Entity;


use FOS\UserBundle\Model\Group as BaseGroup;
use Doctrine\ORM\Mapping as ORM;

/**
 * Group
 *
 * @ORM\Table(name="fos_group")
 * @ORM\Entity(repositoryClass="PasswordManager\Bundle\UserBundle\Repository\GroupRepository")
 */

class Group extends BaseGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @ORM\ManyToMany(targetEntity="PasswordManager\Bundle\UserBundle\Entity\User")
     *
     *
     */
    protected $users;

    /**
     * Add user
     *
     * @param \PasswordManager\Bundle\UserBundle\Entity\User $user
     *
     * @return Group
     */
    public function addUser(\PasswordManager\Bundle\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \PasswordManager\Bundle\UserBundle\Entity\User $user
     */
    public function removeUser(\PasswordManager\Bundle\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}

<?php

namespace PasswordManager\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Contact
 *
 * @ORM\Table(name="contact")
 * @ORM\Entity(repositoryClass="PasswordManager\Bundle\CoreBundle\Repository\ContactRepository")
 */
class Contact
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *@Assert\Length(min=6, minMessage=" Votre sujet doit comporter au minimum {{ limit }} caractères.")
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @ORM\ManyToOne(targetEntity="PasswordManager\Bundle\UserBundle\Entity\User")
     *
     */
    private $user;

    /**
     * Get id
     *
     * @return int
     */

    /**
     * @ORM\ManyToOne(targetEntity="PasswordManager\Bundle\CoreBundle\Entity\CategoryContact")
     *
     */
    private $categorieContact;


    public function getId()
    {
        return $this->id;
    }

    /**
     * Set subject
     *
     * @param string $subject
     *
     * @return Contact
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return Contact
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set user
     *
     * @param \PasswordManager\Bundle\UserBundle\Entity\User $user
     *
     * @return Contact
     */
    public function setUser(\PasswordManager\Bundle\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PasswordManager\Bundle\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set categorieContact
     *
     * @param \PasswordManager\Bundle\CoreBundle\Entity\CategoryContact $categorieContact
     *
     * @return Contact
     */
    public function setCategorieContact(\PasswordManager\Bundle\CoreBundle\Entity\CategoryContact $categorieContact = null)
    {
        $this->categorieContact = $categorieContact;

        return $this;
    }

    /**
     * Get categorieContact
     *
     * @return \PasswordManager\Bundle\CoreBundle\Entity\CategoryContact
     */
    public function getCategorieContact()
    {
        return $this->categorieContact;
    }
    /**
     * Get motive
     *
     * @return \PasswordManager\Bundle\CoreBundle\Entity\CategoryContact
     */
    public function getMotiveCategorieContact($category)
    {
        return $category->motive;
    }
}

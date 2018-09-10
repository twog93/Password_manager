<?php

namespace PasswordManager\Bundle\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Application
 *
 * @ORM\Table(name="application")
 * @ORM\Entity(repositoryClass="PasswordManager\Bundle\PlatformBundle\Entity\ApplicationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Application
{

  /**
   * @ORM\ManyToOne(targetEntity="PasswordManager\Bundle\PlatformBundle\Entity\Password", inversedBy="applications")
   * @ORM\JoinColumn(nullable=false)
   */

  private $password;
/**
   * @ORM\PrePersist
   */

  public function increase()

  {

    $this->getPassword()->increaseApplication();

  }


  /**
   * @ORM\PreRemove
   */

  public function decrease()

  {

    $this->getPassword()->decreaseApplication();

  }



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
     *
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;


    public function __construct()

    {

    // Par défaut, la date de l'annonce est la date d'aujourd'hui

    $this->date = new \Datetime();

    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Application
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Application
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Application
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set password
     *
     * @param \PasswordManager\Bundle\PlatformBundle\Entity\Password $password
     *
     * @return Application
     */
    public function setPassword(\PasswordManager\Bundle\PlatformBundle\Entity\Password $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return \PasswordManager\Bundle\PlatformBundle\Entity\Password
     */
    public function getPassword()
    {
        return $this->password;
    }
}

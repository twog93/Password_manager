<?php

namespace PasswordManager\Bundle\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Ambta\DoctrineEncryptBundle\Configuration\Encrypted;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Advert
 *
 * @ORM\Table(name="advert")
 * @ORM\Entity(repositoryClass="PasswordManager\Bundle\PlatformBundle\Entity\AdvertRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Advert
{

	/**
   * @Gedmo\Slug(fields={"title"})
   * @ORM\Column(length=128, unique=true)
   */
   private $slug;

    /**
    * @ORM\ManyToMany(targetEntity="PasswordManager\Bundle\PlatformBundle\Entity\Category", cascade={"persist"})
     * @Assert\NotBlank()
    */

   private $categories;


    /**
     * @ORM\ManyToMany(targetEntity="PasswordManager\Bundle\UserBundle\Entity\Group", cascade={"persist"})
     * @Assert\NotBlank()
     */

    private $groups;

    /**
    * @ORM\OneToMany(targetEntity="PasswordManager\Bundle\PlatformBundle\Entity\Application", mappedBy="advert")*
    */
    private $applications;

    /**
     * @ORM\ManyToOne(targetEntity="PasswordManager\Bundle\UserBundle\Entity\User")
     *
     */
    private $user;

    /**

     * @ORM\Column(name="shared", type="boolean")

     */
    private $shared = false;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

     /**
   * @ORM\Column(name="nb_applications", type="integer")
   */
    private $nbApplications = 0;

    /**
     * @var string
     * @Assert\Length(min=5, minMessage="Le titre doit faire au moins {{ limit }} caractères.")
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Assert\Length(min=5, minMessage="L'url doit faire au moins {{ limit }} caractères.")
     * @Assert\Url(message = "L'url '{{ value }}' n'est pas une url valide de type 'http:'")
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var string
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     * @Assert\Length(min=5, minMessage="Pour des raisons de sécurité, votre mot de passe ne peut être inférieur à {{ limit }} caractères.")
     * @Encrypted
     * @var int
     */
    private $password;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
   * @ORM\Column(name="updated_at", type="datetime", nullable=true)
   */

    private $updatedAt;


	public function __construct()

  {

    $this->date = new \Datetime();
    $this->categories = new ArrayCollection();
    $this->applications = new arrayCollection();
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return advert
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
     * Set title
     *
     * @param string $title
     *
     * @return advert
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Advert
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

    public function addCategory(Category $category){

    $this->categories[] = $category;

    return $this;
    }

    public function removeCategory(Category $category){

    $this->categories->removeElement($category);

    }

    public function getCategories()

  {

    return $this->categories;

  }

  public function addApplication(Application $application)

  {

    $this->applications[] = $application;

    $application->setAdvert($this);

    return $this;

  }


  public function removeApplication(Application $application)

  {

    $this->applications->removeElement($application);

  }


  public function getApplications()

  {

    return $this->applications;

  }

  /**
 * @ORM\PreUpdate
 */
  public function updateDate()

   {

    $this->setUpdatedAt(new \Datetime());

   }



    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Advert
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }



    public function increaseApplication()

  {

    $this->nbApplications++;

  }


  public function decreaseApplication()

  {

    $this->nbApplications--;

  }

    /**
     * Set content
     *
     * @param string $user
     *
     * @return user
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Advert
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set nbApplications
     *
     * @param integer $nbApplications
     *
     * @return Advert
     */
    public function setNbApplications($nbApplications)
    {
        $this->nbApplications = $nbApplications;

        return $this;
    }

    /**
     * Get nbApplications
     *
     * @return integer
     */
    public function getNbApplications()
    {
        return $this->nbApplications;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Advert
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return Advert
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Advert
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add group
     *
     * @param \PasswordManager\Bundle\UserBundle\Entity\Group $group
     *
     * @return Advert
     */
    public function addGroup(\PasswordManager\Bundle\UserBundle\Entity\Group $group)
    {
        $this->groups[] = $group;

        return $this;
    }

    /**
     * Remove group
     *
     * @param \PasswordManager\Bundle\UserBundle\Entity\Group $group
     */
    public function removeGroup(\PasswordManager\Bundle\UserBundle\Entity\Group $group)
    {
        $this->groups->removeElement($group);
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Set shared
     *
     * @param boolean $shared
     *
     * @return Advert
     */
    public function setShared($shared)
    {
        $this->shared = $shared;

        return $this;
    }

    /**
     * Get shared
     *
     * @return boolean
     */
    public function getShared()
    {
        return $this->shared;
    }
}

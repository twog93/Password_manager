<?php
/**
 * Created by PhpStorm.
 * User: duveau
 * Date: 20/02/18
 * Time: 10:59
 */

namespace PasswordManager\Bundle\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CategoryContact
 *
 * @ORM\Table(name="category_contact")
 * @ORM\Entity(repositoryClass="PasswordManager\Bundle\CoreBundle\Entity\CategoryContactRepository")
 */
class CategoryContact
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
     *
     * @ORM\Column(name="motive", type="string", length=255)
     */
    private $motive;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set motive
     *
     * @param string $motive
     *
     * @return CategoryContact
     */
    public function setMotive($motive)
    {
        $this->motive = $motive;

        return $this;
    }

    /**
     * Get motive
     *
     * @return string
     */
    public function getMotive()
    {
        return $this->motive;
    }
}

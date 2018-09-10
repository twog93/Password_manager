<?php

namespace PasswordManager\Bundle\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PasswordSkill
 *
 * @ORM\Table(name="password_skill")
 * @ORM\Entity(repositoryClass="PasswordManager\Bundle\PlatformBundle\Repository\PasswordSkillRepository")
 */
class PasswordSkill
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
     * @ORM\Column(name="level", type="string", length=255)
     */
    private $level;

    /**
   * @ORM\ManyToOne(targetEntity="PasswordManager\Bundle\PlatformBundle\Entity\Password")
   * @ORM\JoinColumn(nullable=false)
   */

    private $password;

     /**
   * @ORM\ManyToOne(targetEntity="PasswordManager\Bundle\PlatformBundle\Entity\Skill")
   * @ORM\JoinColumn(nullable=false)
   */
    private $skill;

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
     * Set level
     *
     * @param string $level
     *
     * @return PasswordSkill
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set password
     *
     * @param \PasswordManager\Bundle\PlatformBundle\Entity\Password $password
     *
     * @return PasswordSkill
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

    /**
     * Set skill
     *
     * @param \PasswordManager\Bundle\PlatformBundle\Entity\Skill $skill
     *
     * @return PasswordSkill
     */
    public function setSkill(\PasswordManager\Bundle\PlatformBundle\Entity\Skill $skill)
    {
        $this->skill = $skill;

        return $this;
    }

    /**
     * Get skill
     *
     * @return \PasswordManager\Bundle\PlatformBundle\Entity\Skill
     */
    public function getSkill()
    {
        return $this->skill;
    }

    public function getSkillsList(){


    }
}

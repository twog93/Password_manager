<?php

namespace PasswordManager\Bundle\PlatformBundle\Antispam;

class PasswordManagerAntispam
{

   private $mailer,$locale,$minlength;
   
   public function __construct(\Swift_Mailer $mailer, $locale, $minLength)

  {

    $this->mailer    = $mailer;

    $this->locale    = $locale;

    $this->minLength = (int) $minLength;

  }
     /**
   * Vérifie si le texte est un spam ou non
   *
   * @param string $text
   * @return bool
   // */
  public function isSpam($text)
  {
    return strlen($text) > $this->minLength;
  }
}
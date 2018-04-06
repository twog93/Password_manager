<?php

namespace PasswordManager\Bundle\UserBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\ConnectionException;
use Symfony\Component\Ldap\LdapInterface;
use Symfony\Component\Security\Core\Exception\InvalidArgumentException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use PasswordManager\Bundle\UserBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class LdapUserProvider implements UserProviderInterface
{
    private $ldap;
    private $baseDn;
    private $searchDn;
    private $searchPassword;
    private $defaultRoles;
    private $defaultSearch;
    private $filterAdmin;
    private $passwordAttribute;
    private $em;

    /**
     * @param LdapInterface $ldap
     * @param string        $baseDn
     * @param string        $searchDn
     * @param string        $searchPassword
     * @param array         $defaultRoles
     * @param string        $uidKey
     * @param string        $filter
     * @param string        $passwordAttribute
     */
    public function __construct(LdapInterface $ldap, $baseDn, $searchDn = null, $searchPassword = null, array $defaultRoles = array(), $uidKey = 'uid', EntityManager $em, $filterAdmin = '(memberUid={username})', $filter = '({uid_key}={username})', $passwordAttribute = null)
    {

        $this->ldap = $ldap;
        $this->baseDn = $baseDn;
        $this->searchDn = $searchDn;
        $this->searchPassword = $searchPassword;
        $this->defaultRoles = $defaultRoles;
        $this->em = $em;
        $this->defaultSearch = str_replace('{uid_key}', $uidKey, $filter);
        $this->filterAdmin = $filterAdmin;
        $this->passwordAttribute = $passwordAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function loadUserByUsername($username)
    {
        try {
            $this->ldap->bind($this->searchDn, $this->searchPassword);
            $username = $this->ldap->escape($username, '', LdapInterface::ESCAPE_FILTER);
            $query = str_replace('{username}', $username, $this->defaultSearch);
            $search = $this->ldap->query($this->baseDn, $query);
        } catch (ConnectionException $e) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username), 0, $e);
        }

        $entries = $search->execute();
        $count = count($entries);
        if (!$count) {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }

        if ($count > 1) {
            throw new UsernameNotFoundException('More than one user found');
        }

        // Lorsque nous avons vérifier que l'utilisateur existe bien dans le LDAP on peut le loader/créer son compte utilisateur
        return $this->loadUser($username, $entries[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $userRepository = $this->em->getRepository("AppBundle:User");
        $user = $userRepository->findOneBy(array("username" => $user->getUsername()));

        if ($user === null) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        return $class === User::class;
    }

    /**
     * Loads a user from an LDAP entry.
     *
     * @param string $username
     * @param Entry  $entry
     *
     * @return User
     */
    protected function loadUser($username, Entry $entry)
    {

        dump($this->getPassword($entry));
        dump($this->passwordAttribute);
        $userRepository = $this->em->getRepository("PasswordManagerUserBundle:User");
        $user = $userRepository->findOneBy(array("username" => $username));
        if ($user === null) {
            $user = new User();
            //$user->setFirstname($entry->getAttribute("givenName")[0]);
            $user->setEmail($entry->getAttribute("mail")[0]);
            $user->setUsername($entry->getAttribute("mail")[0]);
            $user->setPassword("test");
            $user->setRoles($this->defaultRoles);
           if (!$this->em->isOpen()) {
                $this->em = $this->em->create(
                    $this->em->getConnection(),
                    $this->em->getConfiguration()
                );
            }
            $this->em->persist($user);
            $this->em->flush();
        } else {

            $this->em->flush();
        }

        return $user;
    }

    /**
     * Fetches the password from an LDAP entry.
     *
     * @param null|Entry $entry
     */
    private function getPassword(Entry $entry)
    {
        if (null === $this->passwordAttribute) {
            return;
        }

        if (!$entry->hasAttribute($this->passwordAttribute)) {
            throw new InvalidArgumentException(sprintf('Missing attribute "%s" for user "%s".', $this->passwordAttribute, $entry->getDn()));
        }

        $values = $entry->getAttribute($this->passwordAttribute);

        if (1 !== count($values)) {
            throw new InvalidArgumentException(sprintf('Attribute "%s" has multiple values.', $this->passwordAttribute));
        }

        return $values[0];
    }
}
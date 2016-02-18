<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

class User implements UserInterface, \Serializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max = 4096)
     * @var string
     */
    private $plainPassword;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     * @var string
     */
    private $email;

    /**
     * @var bool
     */
    private $active;

    /**
     * @var \DateTime
     */
    private $lastLogin;

    public function __construct()
    {
        $this->active = true;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @param $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param $password
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * @return \DateTime
     */
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    public function updateLogin()
    {
        $this->lastLogin = new \DateTime();
    }

    /**
     * @return null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        return null;
    }

    /**
     * @see \Serializable::serialize()
     * @return string
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
                $this->active,
            ]
        );
    }

    /**
     * @see \Serializable::unserialize()
     * @param string $serialized
     * @return UserInterface|void
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->password,
            $this->active,
            ) = unserialize($serialized);

        return $this;
    }
}

<?php

namespace Test\AppBundle\Entity;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\UserInterface;

class UserTest extends KernelTestCase
{
    public function testGettersAndSetters()
    {
        $username = 'foo';
        $email = 'foo@example.org';
        $password = 'bar';

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setPassword($password);
        $user->updateLogin();
        $user->setActive(true);

        $this->assertEquals('', $user->getId());
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals(true, $user->isActive());
        $this->assertEquals($password, $user->getPlainPassword());
        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertInstanceOf(\DateTime::class, $user->getLastLogin());
        $this->assertEquals(null, $user->eraseCredentials());
        $this->assertInstanceOf(UserInterface::class, $user->unserialize($user->serialize()));
    }
}

<?php

namespace Test\AppBundle\Entity;

use AppBundle\Entity\User;
use AppBundle\Entity\UserInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function testGettersAndSetters()
    {
        $username = 'foo';
        $slug = 'foo';
        $email = 'foo@example.org';
        $password = 'bar';
        $gender = 'm';
        $homepage = 'http://www.example.org';
        $avatar = 'avatar.jpg';

        $user = new User();
        $user->setUsername($username);
        $user->setSlug($slug);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setPassword($password);
        $user->setGender($gender);
        $user->setHomepage($homepage);
        $user->setBirthday(new \DateTime());
        $user->setAvatar($avatar);
        $user->updateLogin();
        $user->setActive(true);

        $this->assertEquals('', $user->getId());
        $this->assertEquals($username, $user->getUsername());
        $this->assertEquals($slug, $user->getSlug());
        $this->assertEquals($email, $user->getEmail());
        $this->assertEquals(true, $user->isActive());
        $this->assertEquals($password, $user->getPlainPassword());
        $this->assertEquals(null, $user->getSalt());
        $this->assertEquals($password, $user->getPassword());
        $this->assertEquals($gender, $user->getGender());
        $this->assertEquals($homepage, $user->getHomepage());
        $this->assertInstanceOf(\DateTime::class, $user->getBirthday());
        $this->assertEquals($avatar, $user->getAvatar());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertInstanceOf(\DateTime::class, $user->getLastLogin());
        $this->assertEquals(null, $user->eraseCredentials());
        $this->assertInstanceOf(UserInterface::class, $user->unserialize($user->serialize()));
        $this->assertEquals($username, $user);
        $this->assertInstanceOf(UserInterface::class, $user);
    }
}

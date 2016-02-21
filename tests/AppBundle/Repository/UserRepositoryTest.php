<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Tests\AppBundle\IntegrationWebTestCase;

class UserRepositoryTest extends IntegrationWebTestCase
{
    public function dataProviderTestCreateAndDeleteUser()
    {
        return [
            ['foo'],
            ['mail@example.org'],
        ];
    }

    /**
     * @dataProvider dataProviderTestCreateAndDeleteUser
     * @param $username
     */
    public function testCreateAndDeleteUser($username)
    {
        $user = $this->getUserRepository()->getNew();
        $user->setUsername('foo');
        $user->setSlug('foo');
        $user->setPassword('password');
        $user->setEmail('mail@example.org');
        $this->assertInstanceOf(UserInterface::class, $user);

        $this->getUserRepository()->save($user);

        $user = $this->getUserRepository()->loadUserByUsername($username);
        $this->assertInstanceOf(UserInterface::class, $user);

        $this->getUserRepository()->delete($user);

        $this->setExpectedException('Symfony\Component\Security\Core\Exception\UsernameNotFoundException');
        $this->getUserRepository()->loadUserByUsername($username);
    }

    /** @return UserRepository */
    private function getUserRepository()
    {
        return $this->em->getRepository('AppBundle:User');
    }
}

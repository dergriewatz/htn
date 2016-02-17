<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Tests\AppBundle\IntegrationWebTestCase;

class UserRepositoryTest extends IntegrationWebTestCase
{
    /**
     * @dataProvider loadUserByUsernameProvider
     * @param $username
     * @param $expected
     */
    public function testLoadUserByUsername($username, $expected)
    {
        $user = $this->getUserRepository()->loadUserByUsername($username);

        $this->assertInstanceOf($expected, $user);
    }

    public function loadUserByUsernameProvider()
    {
        return [
            ['foo', UserInterface::class],
            ['bar', UsernameNotFoundException::class],
        ];
    }

    /** @return UserRepository */
    private function getUserRepository()
    {
        return $this->em->getRepository('AppBundle:User');
    }
}

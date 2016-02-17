<?php

namespace Test\AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\UserService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Tests\AppBundle\IntegrationWebTestCase;

class UserServiceTest extends IntegrationWebTestCase
{
    public function testCreateUser()
    {
        $userService = new UserService(
            $this->prophesize(UserRepository::class),
            $this->prophesize(UserPasswordEncoderInterface::class)
        );

        $user = $this->prophesize(User::class);

        $userService->createUser($user);
    }
}

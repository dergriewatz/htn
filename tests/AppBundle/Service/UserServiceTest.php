<?php

namespace Test\AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\UserService;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Tests\AppBundle\IntegrationWebTestCase;

class UserServiceTest extends IntegrationWebTestCase
{
    public function testGetNewUser()
    {
        $user = $this->prophesize(UserInterface::class);

        $repository = $this->prophesize(UserRepository::class);
        $repository->getNew()->willReturn($user->reveal());

        $userService = new UserService(
            $repository->reveal(),
            $this->prophesize(UserPasswordEncoderInterface::class)->reveal()
        );

        $this->assertInstanceOf(UserInterface::class, $userService->getNewUser($user->reveal()));
    }

    public function testUpdateUser()
    {
        $user = $this->prophesize(User::class);
        $user->getPlainPassword()->willReturn('password');
        $user->setPassword('encoded_password')->shouldBeCalled();

        $repository = $this->prophesize(UserRepository::class);
        $repository->save($user->reveal())->shouldBeCalled();

        $userPasswordEncoder = $this->prophesize(UserPasswordEncoderInterface::class);
        $userPasswordEncoder->encodePassword($user->reveal(), Argument::any())->willReturn('encoded_password');

        $userService = new UserService(
            $repository->reveal(),
            $userPasswordEncoder->reveal()
        );

        $userService->updateUser($user->reveal());
    }
}

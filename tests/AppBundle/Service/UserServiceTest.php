<?php

namespace Test\AppBundle\Service;

use AppBundle\Entity\User;
use AppBundle\Entity\UserInterface;
use AppBundle\Repository\UserRepository;
use AppBundle\Service\UserService;
use AppBundle\Utils\Slugger;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Tests\AppBundle\IntegrationWebTestCase;

class UserServiceTest extends IntegrationWebTestCase
{
    public function testFindOneBySlug()
    {
        $slug = 'foo';

        $user = $this->prophesize(User::class);

        $repository = $this->prophesize(UserRepository::class);
        $repository->findOneBy(['slug' => $slug])->willReturn($user->reveal());

        $userService = new UserService(
            $repository->reveal(),
            $this->prophesize(UserPasswordEncoderInterface::class)->reveal(),
            $this->prophesize(Slugger::class)->reveal()
        );

        $this->assertInstanceOf(UserInterface::class, $userService->findOneBySlug($slug));
    }

    public function testFindOneByUsername()
    {
        $username = 'foo';

        $user = $this->prophesize(User::class);

        $repository = $this->prophesize(UserRepository::class);
        $repository->findOneByUsernameOrSlug($username)->willReturn($user->reveal());

        $userService = new UserService(
            $repository->reveal(),
            $this->prophesize(UserPasswordEncoderInterface::class)->reveal(),
            $this->prophesize(Slugger::class)->reveal()
        );

        $this->assertInstanceOf(UserInterface::class, $userService->findOneByUsername($username));
    }

    public function testGetNewUser()
    {
        $user = $this->prophesize(User::class);

        $repository = $this->prophesize(UserRepository::class);
        $repository->getNew()->willReturn($user->reveal());

        $userService = new UserService(
            $repository->reveal(),
            $this->prophesize(UserPasswordEncoderInterface::class)->reveal(),
            $this->prophesize(Slugger::class)->reveal()
        );

        $this->assertInstanceOf(UserInterface::class, $userService->getNewUser($user->reveal()));
    }

    public function testUpdateUser()
    {
        $user = $this->prophesize(User::class);
        $user->getPlainPassword()->willReturn('password');
        $user->setPassword('encoded_password')->shouldBeCalled();
        $user->getUsername()->willReturn('foo');
        $user->setSlug('foo')->shouldBeCalled();

        $repository = $this->prophesize(UserRepository::class);
        $repository->save($user->reveal())->shouldBeCalled();

        $userPasswordEncoder = $this->prophesize(UserPasswordEncoderInterface::class);
        $userPasswordEncoder->encodePassword($user->reveal(), Argument::any())->willReturn('encoded_password');

        $slugger = $this->prophesize(Slugger::class);
        $slugger->slugify(Argument::any())->willReturn('foo');

        $userService = new UserService(
            $repository->reveal(),
            $userPasswordEncoder->reveal(),
            $slugger->reveal()
        );

        $userService->updateUser($user->reveal());
    }
}

<?php

namespace AppBundle\Service;

use AppBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserService
{
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     */
    public function __construct(UserRepository $repository, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->repository = $repository;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    /**
     * @return UserInterface
     */
    public function createUser()
    {
        return $this->repository->create();
    }

    /**
     * @param UserInterface $user
     */
    public function updateUser(UserInterface $user)
    {
        $password = $this->userPasswordEncoder->encodePassword($user, $user->getPlainPassword());
        $user->setPassword($password);

        $this->repository->save($user);
    }
}

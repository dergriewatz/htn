<?php

namespace AppBundle\Service;

use AppBundle\Repository\UserRepository;
use AppBundle\Utils\Slugger;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    /** @var UserRepository */
    private $repository;

    /** @var UserPasswordEncoderInterface */
    private $userPasswordEncoder;

    /** @var Slugger */
    private $slugger;

    /**
     * @param UserRepository $repository
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     * @param Slugger $slugger
     */
    public function __construct(
        UserRepository $repository,
        UserPasswordEncoderInterface $userPasswordEncoder,
        Slugger $slugger
    ) {
        $this->repository = $repository;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->slugger = $slugger;
    }

    /**
     * @param string $username
     * @return UserInterface
     */
    public function getUserByUsername($username)
    {
        return $this->repository->findOneBy(['slug' => $username]);
    }

    /**
     * @return UserInterface
     */
    public function getNewUser()
    {
        return $this->repository->getNew();
    }

    /**
     * @param UserInterface $user
     */
    public function updateUser(UserInterface $user)
    {
        if ($user->getPlainPassword()) {
            $password = $this->userPasswordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
        }

        $slug = $this->slugger->slugify($user->getUsername());
        $user->setSlug($slug);

        $this->repository->save($user);
    }
}

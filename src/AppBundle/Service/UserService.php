<?php

namespace AppBundle\Service;

use AppBundle\Entity\UserInterface;
use AppBundle\Repository\UserRepository;
use AppBundle\Utils\Slugger;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @param string $slug
     * @return UserInterface
     */
    public function findOneBySlug($slug)
    {
        return $this->repository->findOneBy(['slug' => $slug]);
    }

    /**
     * @param string $username
     * @return UserInterface
     */
    public function findOneByUsername($username)
    {
        return $this->repository->findOneByUsernameOrSlug($username);
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

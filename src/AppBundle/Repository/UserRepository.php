<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Entity\UserInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    /**
     * @param string $username
     * @return UserInterface|null
     * @throws NonUniqueResultException|UsernameNotFoundException
     */
    public function loadUserByUsername($username)
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            $message = sprintf(
                'Unable to find an active AppBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message);
        }

        return $user;
    }

    /**
     * @param string $username
     * @return UserInterface|null
     * @throws NonUniqueResultException|UsernameNotFoundException
     */
    public function findOneByUsernameOrSlug($username)
    {
        $user = $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.slug = :slug')
            ->setParameter('username', $username)
            ->setParameter('slug', $username)
            ->getQuery()
            ->getOneOrNullResult();

        if (null === $user) {
            $message = sprintf(
                'Unable to find an active AppBundle:User object identified by "%s".',
                $username
            );
            throw new UsernameNotFoundException($message);
        }

        return $user;
    }

    /**
     * @return UserInterface
     */
    public function getNew()
    {
        return new User();
    }

    /**
     * @param UserInterface $user
     */
    public function save(UserInterface $user)
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush($user);
    }

    /**
     * @param UserInterface $user
     */
    public function delete(UserInterface $user)
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush($user);
    }
}

<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\UserInterface;
use AppBundle\Repository\UserRepository;
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

    public function dataProviderTestFindOneByUsernameOrSlug()
    {
        return [
            ['Foo&Bar'],
            ['foo-bar'],
        ];
    }

    /**
     * @dataProvider dataProviderTestFindOneByUsernameOrSlug
     * @param $username
     */
    public function testFindOneByUsernameOrSlug($username)
    {
        $user = $this->getUserRepository()->getNew();
        $user->setUsername('Foo&Bar');
        $slug = $this->getService('app.slugger')->slugify('Foo&Bar');
        $user->setSlug($slug);
        $user->setPassword('password');
        $user->setEmail('mail@example.org');
        $this->assertInstanceOf(UserInterface::class, $user);

        $this->getUserRepository()->save($user);

        $user = $this->getUserRepository()->findOneByUsernameOrSlug($username);
        $this->assertInstanceOf(UserInterface::class, $user);

        $this->getUserRepository()->delete($user);

        $this->setExpectedException('Symfony\Component\Security\Core\Exception\UsernameNotFoundException');
        $this->getUserRepository()->findOneByUsernameOrSlug($username);
    }

    /** @return UserRepository */
    private function getUserRepository()
    {
        return $this->em->getRepository('AppBundle:User');
    }
}

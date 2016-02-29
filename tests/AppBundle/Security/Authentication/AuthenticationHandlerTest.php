<?php

namespace Test\AppBundle\Security\Authentication;

use AppBundle\Entity\User;
use AppBundle\Security\Authentication\AuthenticationHandler;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\HttpUtils;
use Tests\AppBundle\IntegrationWebTestCase;

class AuthenticationHandlerTest extends IntegrationWebTestCase
{
    public function testOnAuthenticationSuccess()
    {
        $user = $this->prophesize(User::class);
        $user->updateLogin()->shouldBeCalled();

        $token = $this->prophesize(TokenInterface::class);
        $token->getUser()->willReturn($user->reveal());

        $entityManager = $this->prophesize(EntityManager::class);
        $entityManager->flush()->shouldBeCalled();

        $handler = new AuthenticationHandler(
            $entityManager->reveal(),
            $this->prophesize(HttpUtils::class)->reveal(),
            []
        );

        $handler->onAuthenticationSuccess(
            $this->prophesize(Request::class)->reveal(),
            $token->reveal()
        );
    }
}

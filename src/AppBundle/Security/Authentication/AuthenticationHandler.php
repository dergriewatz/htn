<?php

namespace AppBundle\Security\Authentication;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;

class AuthenticationHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * @var EntityManager
     */
    private $manager;

    /**
     * @param EntityManager $manager
     * @param HttpUtils $httpUtils
     * @param array $options
     */
    public function __construct(EntityManager $manager, HttpUtils $httpUtils, array $options = array())
    {
        $this->manager = $manager;

        parent::__construct($httpUtils, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $token->getUser()->updateLogin();
        $this->manager->flush();

        return parent::onAuthenticationSuccess($request, $token);
    }
}

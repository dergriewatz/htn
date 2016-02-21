<?php

namespace AppBundle\Controller;

use AppBundle\Form\ProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('user/profile.html.twig', ['user' => $this->getUser()]);
    }

    /**
     * @param string $username
     * @return RedirectResponse|Response
     */
    public function profileAction($username)
    {
        if (!$user = $this->get('user.service')->getUserByUsername($username)) {
            return $this->redirectToRoute('user_overview');
        }

        return $this->render('user/profile.html.twig', ['user' => $user]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('user.service')->updateUser($user);

            return $this->redirectToRoute('user_overview');
        }

        return $this->render('user/index.html.twig', ['form' => $form->createView()]);
    }
}

<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Mail;
use AppBundle\Form\MailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MailController extends Controller
{
    /**
     * @return Response
     */
    public function inboxAction()
    {
        $mails = $this->get('mail.service')->findByLabel(Mail::LABEL_INBOX);

        return $this->render('mail/overview.html.twig', ['mails' => $mails]);
    }

    /**
     * @return Response
     */
    public function outboxAction()
    {
        $mails = $this->get('mail.service')->findByLabel(Mail::LABEL_OUTBOX);

        return $this->render('mail/overview.html.twig', ['mails' => $mails]);
    }

    /**
     * @return Response
     */
    public function archiveAction()
    {
        $mails = $this->get('mail.service')->findByLabel(Mail::LABEL_ARCHIVE);

        return $this->render('mail/overview.html.twig', ['mails' => $mails]);
    }

    /**
     * @param string $id
     * @return Response
     */
    public function showAction($id)
    {
        $mail = $this->get('mail.service')->findOneById($id);

        $this->get('mail.service')->updateReadStatus($mail);

        return $this->render('mail/show.html.twig', ['mail' => $mail]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(MailType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('mail.service')->sendMail($form->getData());

            return $this->redirectToRoute('mail_new');
        }

        return $this->render('mail/create.html.twig', ['form' => $form->createView()]);
    }
}

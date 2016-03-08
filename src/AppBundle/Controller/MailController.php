<?php

namespace AppBundle\Controller;

use AppBundle\Dto\MailDto;
use AppBundle\Entity\Mail;
use AppBundle\Form\MailType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MailController extends Controller
{
    /**
     * @return Response
     */
    public function inboxAction()
    {
        $mails = $this->get('mail.service')->getMailsByLabel(Mail::LABEL_INBOX);

        return $this->render('mail/overview.html.twig', ['mails' => $mails]);
    }

    /**
     * @return Response
     */
    public function outboxAction()
    {
        $mails = $this->get('mail.service')->getMailsByLabel(Mail::LABEL_OUTBOX);

        return $this->render('mail/overview.html.twig', ['mails' => $mails]);
    }

    /**
     * @return Response
     */
    public function archiveAction()
    {
        $mails = $this->get('mail.service')->getMailsByLabel(Mail::LABEL_ARCHIVE);

        return $this->render('mail/overview.html.twig', ['mails' => $mails]);
    }

    /**
     * @param string $id
     * @return Response
     */
    public function showAction($id)
    {
        if (!$mail = $this->get('mail.service')->getMailById($id)) {
            throw new NotFoundHttpException(sprintf('Mail with id "%s" not found', $id));
        }

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

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function replyAction(Request $request, $id)
    {
        if (!$mail = $this->get('mail.service')->getReplyMail($id)) {
            throw new NotFoundHttpException(sprintf('Mail with id "%s" not found', $id));
        }

        $mailDto = $this->get('mail.service')->updateForReply(MailDto::fromMail($mail));

        $form = $this->createForm(MailType::class, $mailDto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('mail.service')->sendMail($form->getData());

            return $this->redirectToRoute('mail_reply', ['id' => $mail->getId()]);
        }

        return $this->render('mail/create.html.twig', ['form' => $form->createView()]);
    }
}

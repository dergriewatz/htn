<?php

namespace AppBundle\Service;

use AppBundle\Dto\MailDto;
use AppBundle\Entity\Mail;
use AppBundle\Repository\MailRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MailService
{
    /** @var MailRepository */
    private $repository;

    /** @var UserService */
    private $userService;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /**
     * @param MailRepository $repository
     * @param UserService $userService
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(
        MailRepository $repository,
        UserService $userService,
        TokenStorageInterface $tokenStorage
    ) {
        $this->repository = $repository;
        $this->userService = $userService;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param string $label
     * @return \AppBundle\Entity\Mail[]
     */
    public function getMailsByLabel($label)
    {
        return $this->repository->findBy(
            [
                'user' => $this->tokenStorage->getToken()->getUser(),
                'label' => $label,
            ]
        );
    }

    /**
     * @param string $id
     * @return Mail
     */
    public function getMailById($id)
    {
        return $this->repository->findOneBy(
            [
                'user' => $this->tokenStorage->getToken()->getUser(),
                'id' => $id,
            ]
        );
    }

    /**
     * @param string $id
     * @return Mail|null
     */
    public function getReplyMail($id)
    {
        return $this->repository->findAnswerableMailById($id, $this->tokenStorage->getToken()->getUser());
    }

    /**
     * @param Mail $mail
     */
    public function updateReadStatus(Mail $mail)
    {
        if (!$mail->isRead()) {
            $mail->markAsRead();
            $this->repository->save($mail);
        }
    }

    /**
     * @param MailDto $mailDto
     * @return MailDto
     */
    public function updateForReply(MailDto $mailDto)
    {
        $mailDto->setSubject(
            $this->modifyReplySubject($mailDto->getSubject())
        );

        $mailDto->setText(
            $this->modifyReplyText($mailDto->getText(), $mailDto->getSender())
        );

        return $mailDto;
    }

    /**
     * @param string $subject
     * @return string
     */
    private function modifyReplySubject($subject)
    {
        preg_match('/^Re(\[(\d+)\])?\:/i', $subject, $matches);

        if ($matches) {
            $subject = str_replace($matches[0], '', $subject);
        }

        if (1 === count($matches)) {
            return sprintf('Re[2]:%s', $subject);
        }

        if (3 === count($matches)) {
            return sprintf('Re[%d]:%s', $matches[2] + 1, $subject);
        }

        return sprintf('Re: %s', $subject);
    }

    /**
     * @param string $text
     * @param string $sender
     * @return string
     */
    private function modifyReplyText($text, $sender)
    {
        $text = sprintf(
            "\n\n\n--- Ursprüngliche Nachricht von %s ---\n\n%s",
            $sender,
            $text
        );

        return $text;
    }

    /**
     * @param MailDto $mailDto
     */
    public function sendMail(MailDto $mailDto)
    {
        $receiver = $this->userService->findOneByUsername($mailDto->getReceiver());
        $sender = $this->tokenStorage->getToken()->getUser();

        $mail = $this->repository->createMail(
            $receiver,
            $sender,
            $mailDto->getText(),
            $mailDto->getSubject()
        );
        $this->repository->save($mail);

        $mail = $this->repository->createMail(
            $sender,
            $receiver,
            $mailDto->getText(),
            $mailDto->getSubject(),
            Mail::LABEL_OUTBOX
        );
        $this->repository->save($mail);
    }

    /**
     * @param MailDto $mailDto
     */
    public function sendSystemMail(MailDto $mailDto)
    {
        $receiver = $this->userService->findOneByUsername($mailDto->getReceiver());

        $mail = $this->repository->createMail(
            $receiver,
            null,
            $mailDto->getText(),
            $mailDto->getSubject(),
            Mail::LABEL_INBOX,
            Mail::TYPE_SYSTEM
        );
        $this->repository->save($mail);
    }
}

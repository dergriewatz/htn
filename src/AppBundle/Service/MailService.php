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
    public function findByLabel($label)
    {
        return $this->repository->findBy([
            'user' => $this->tokenStorage->getToken()->getUser(),
            'label' => $label,
        ]);
    }

    /**
     * @param string $id
     * @return Mail
     */
    public function findOneById($id)
    {
        return $this->repository->findOneBy([
            'user' => $this->tokenStorage->getToken()->getUser(),
            'id' => $id
        ]);
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

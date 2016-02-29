<?php

namespace Test\AppBundle\Service;

use AppBundle\Dto\MailDto;
use AppBundle\Entity\Mail;
use AppBundle\Entity\User;
use AppBundle\Repository\MailRepository;
use AppBundle\Service\MailService;
use AppBundle\Service\UserService;
use Prophecy\Argument;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Tests\AppBundle\IntegrationWebTestCase;

class MailServiceTest extends IntegrationWebTestCase
{
    public function testFindByLabel()
    {
        $label = Mail::LABEL_INBOX;

        $token = $this->prophesize(TokenInterface::class);
        $token->getUser()->willReturn($user = $this->prophesize(UserInterface::class)->reveal());

        $tokenStorage = $this->prophesize(TokenStorageInterface::class);
        $tokenStorage->getToken()->willReturn($token);

        $repository = $this->prophesize(MailRepository::class);
        $repository->findBy(['user' => $user, 'label' => $label])->willReturn([$this->prophesize(Mail::class)->reveal()]);

        $mailService = new MailService(
            $repository->reveal(),
            $this->prophesize(UserService::class)->reveal(),
            $tokenStorage->reveal()
        );

        $this->assertInstanceOf(Mail::class, $mailService->findByLabel($label)[0]);
    }

    public function testFindOneById()
    {
        $id = '123';

        $token = $this->prophesize(TokenInterface::class);
        $token->getUser()->willReturn($user = $this->prophesize(UserInterface::class)->reveal());

        $tokenStorage = $this->prophesize(TokenStorageInterface::class);
        $tokenStorage->getToken()->willReturn($token);

        $repository = $this->prophesize(MailRepository::class);
        $repository->findOneBy(['user' => $user, 'id' => $id])->willReturn($this->prophesize(Mail::class)->reveal());

        $mailService = new MailService(
            $repository->reveal(),
            $this->prophesize(UserService::class)->reveal(),
            $tokenStorage->reveal()
        );

        $this->assertInstanceOf(Mail::class, $mailService->findOneById($id));
    }

    public function testUpdateReadStatus()
    {
        $mail = $this->prophesize(Mail::class);
        $mail->isRead()->willReturn(false);
        $mail->markAsRead()->shouldBeCalled();

        $repository = $this->prophesize(MailRepository::class);
        $repository->save($mail->reveal())->shouldBeCalled();

        $mailService = new MailService(
            $repository->reveal(),
            $this->prophesize(UserService::class)->reveal(),
            $this->prophesize(TokenStorageInterface::class)->reveal()
        );

        $mailService->updateReadStatus($mail->reveal());
    }

    public function testSendMail()
    {
        $token = $this->prophesize(TokenInterface::class);
        $token->getUser()->willReturn($user = $this->prophesize(UserInterface::class)->reveal());

        $tokenStorage = $this->prophesize(TokenStorageInterface::class);
        $tokenStorage->getToken()->willReturn($token);

        $userService = $this->prophesize(UserService::class);
        $userService->findOneByUsername(Argument::any())->willReturn($user2 = $this->prophesize(User::class)->reveal());

        $repository = $this->prophesize(MailRepository::class);
        $repository->createMail($user2, $user, Argument::any(), Argument::any())
            ->shouldBeCalled()
            ->willReturn($inboxMail = $this->prophesize(Mail::class));
        $repository->save($inboxMail->reveal())->shouldBeCalled();
        $repository->createMail($user, $user2, Argument::any(), Argument::any(), Mail::LABEL_OUTBOX)
            ->shouldBeCalled()
            ->willReturn($outbox = $this->prophesize(Mail::class));
        $repository->save($outbox->reveal())->shouldBeCalled();

        $mailService = new MailService(
            $repository->reveal(),
            $userService->reveal(),
            $tokenStorage->reveal()
        );

        $mailService->sendMail($this->prophesize(MailDto::class)->reveal());
    }

    public function testSendSystemMail()
    {
        $userService = $this->prophesize(UserService::class);
        $userService->findOneByUsername(Argument::any())->willReturn($user = $this->prophesize(UserInterface::class)->reveal());

        $repository = $this->prophesize(MailRepository::class);
        $repository->createMail($user, null, Argument::any(), Argument::any(), Mail::LABEL_INBOX, Mail::TYPE_SYSTEM)
            ->shouldBeCalled()
            ->willReturn($inboxMail = $this->prophesize(Mail::class));
        $repository->save($inboxMail->reveal())->shouldBeCalled();

        $mailService = new MailService(
            $repository->reveal(),
            $userService->reveal(),
            $this->prophesize(TokenStorageInterface::class)->reveal()
        );

        $mailService->sendSystemMail($this->prophesize(MailDto::class)->reveal());
    }
}

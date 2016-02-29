<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Mail;
use AppBundle\Repository\MailRepository;
use Tests\AppBundle\IntegrationWebTestCase;

class MailRepositoryTest extends IntegrationWebTestCase
{
    public function testCreateAndDeleteUser()
    {
        $user = $this->getService('user.service')->getNewUser();
        $user->setUsername('foo');
        $user->setPassword('password');
        $user->setEmail('mail@example.org');
        $this->getService('user.service')->updateUser($user);

        $mail = $this->getMailRepository()->createMail(
            $user,
            null,
            'text',
            'subject',
            Mail::LABEL_OUTBOX,
            Mail::TYPE_USER
        );
        $this->assertInstanceOf(Mail::class, $mail);

        $this->getMailRepository()->save($mail);

        $mailIdentifier = $mail->getId();

        $mail = $this->getMailRepository()->find($mailIdentifier);
        $this->assertInstanceOf(Mail::class, $mail);

        $this->getMailRepository()->delete($mail);

        $mail = $this->getMailRepository()->find($mailIdentifier);
    }

    /** @return MailRepository */
    private function getMailRepository()
    {
        return $this->em->getRepository('AppBundle:Mail');
    }
}

<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Mail;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class MailRepository extends EntityRepository
{
    /**
     * @param UserInterface $user
     * @param UserInterface|null $user2
     * @param string $text
     * @param string|null $subject
     * @param string $label
     * @param string $type
     * @return Mail
     */
    public function createMail(
        UserInterface $user,
        UserInterface $user2 = null,
        $text,
        $subject = null,
        $label = Mail::LABEL_INBOX,
        $type = Mail::TYPE_USER
    ) {
        $mail = new Mail();
        $mail->setUser($user);
        $mail->setUser2($user2);
        $mail->setSubject($subject);
        $mail->setText($text);
        $mail->setLabel($label);
        $mail->setType($type);
        if (Mail::LABEL_OUTBOX === $label) {
            $mail->markAsRead();
        }

        return $mail;
    }

    /**
     * @param Mail $mail
     */
    public function save(Mail $mail)
    {
        $this->getEntityManager()->persist($mail);
        $this->getEntityManager()->flush($mail);
    }

    /**
     * @param Mail $mail
     */
    public function delete(Mail $mail)
    {
        $this->getEntityManager()->remove($mail);
        $this->getEntityManager()->flush($mail);
    }
}

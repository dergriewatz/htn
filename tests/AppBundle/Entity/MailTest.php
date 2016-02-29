<?php

namespace Test\AppBundle\Entity;

use AppBundle\Entity\Mail;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailTest extends KernelTestCase
{
    public function testGettersAndSetters()
    {
        $subject = 'foo';
        $text = 'foo';
        $label = Mail::LABEL_INBOX;
        $type = Mail::TYPE_USER;

        $mail = new Mail();
        $mail->setUser($this->prophesize(User::class)->reveal());
        $mail->setUser2($this->prophesize(User::class)->reveal());
        $mail->setSubject($subject);
        $mail->setText($text);
        $mail->setLabel($label);
        $mail->setType($type);
        $mail->setSendAt(new \DateTime());
        $mail->markAsRead();

        $this->assertEquals('', $mail->getId());
        $this->assertInstanceOf(User::class, $mail->getUser());
        $this->assertInstanceOf(User::class, $mail->getUser2());
        $this->assertEquals($subject, $mail->getSubject());
        $this->assertEquals($text, $mail->getText());
        $this->assertEquals($label, $mail->getLabel());
        $this->assertEquals($type, $mail->getType());
        $this->assertInstanceOf(\DateTime::class, $mail->getSendAt());
        $this->assertTrue($mail->isRead());

        $mail->markAsUnread();
        $this->assertFalse($mail->isRead());
    }
}

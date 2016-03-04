<?php

namespace Test\AppBundle\Entity;

use AppBundle\Dto\MailDto;
use AppBundle\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MailDtoTest extends KernelTestCase
{
    public function testGettersAndSetters()
    {
        $receiver = 'bar';
        $subject = 'foo';
        $text = 'qux';

        $mailDto = new MailDto();
        $mailDto->setReceiver($receiver);
        $mailDto->setSubject($subject);
        $mailDto->setText($text);

        $this->assertEquals($receiver, $mailDto->getReceiver());
        $this->assertEquals($subject, $mailDto->getSubject());
        $this->assertEquals($text, $mailDto->getText());
    }

    public function dataProviderTestFromMail()
    {
        return [
            [Mail::LABEL_INBOX, 'bar'],
            [Mail::LABEL_OUTBOX, 'baz'],
            [Mail::LABEL_ARCHIVE, 'baz'],
        ];
    }

    /**
     * @dataProvider dataProviderTestFromMail
     * @param $label
     * @param $expectedSender
     */
    public function testFromMail($label, $expectedSender)
    {
        $sender = 'baz';
        $receiver = 'bar';
        $subject = 'foo';
        $text = 'qux';

        $mail = $this->prophesize(Mail::class);
        $mail->getUser()->willReturn($sender);
        $mail->getUser2()->willReturn($receiver);
        $mail->getSubject()->willReturn($subject);
        $mail->getText()->willReturn($text);
        $mail->getLabel()->willReturn($label);

        $mailDto = new MailDto();
        $newMailDto = $mailDto->fromMail($mail->reveal());

        $this->assertEquals($expectedSender, $newMailDto->getSender());
        $this->assertEquals($receiver, $newMailDto->getReceiver());
        $this->assertEquals($subject, $newMailDto->getSubject());
        $this->assertEquals($text, $newMailDto->getText());
    }
}

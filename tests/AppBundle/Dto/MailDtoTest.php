<?php

namespace Test\AppBundle\Entity;

use AppBundle\Dto\MailDto;
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
}

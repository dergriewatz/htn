<?php

namespace AppBundle\Dto;

use AppBundle\Entity\Mail;
use Symfony\Component\Validator\Constraints as Assert;

class MailDto
{
    /**
     * @var string
     */
    private $sender;

    /**
     * @Assert\NotBlank()
     * @var string
     */
    private $receiver;

    /**
     * @var string
     */
    private $subject;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max="4096")
     * @var string
     */
    private $text;

    /**
     * @param Mail $mail
     * @return MailDto
     */
    public static function fromMail(Mail $mail)
    {
        $self = new static();

        $self->setSender($mail->getUser());
        if (Mail::LABEL_INBOX === $mail->getLabel()) {
            $self->setSender($mail->getUser2());
        }

        $self->setReceiver($mail->getUser2());
        $self->setText($mail->getText());
        $self->setSubject($mail->getSubject());

        return $self;
    }

    /**
     * @return mixed
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param mixed $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param string $receiver
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
    }

    /**
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }
}

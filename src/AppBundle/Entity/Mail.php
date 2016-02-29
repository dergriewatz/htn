<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class Mail
{
    const LABEL_INBOX = 'inbox';
    const LABEL_OUTBOX = 'outbox';
    const LABEL_ARCHIVE = 'archive';

    const TYPE_USER = 'user';
    const TYPE_SYSTEM = 'system';

    /**
     * @var string
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var User
     */
    private $user2;

    /**
     * @var string
     */
    private $subject;

    /**
     * @var string
     */
    private $text;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var \DateTime
     */
    private $sendAt;

    /**
     * @var \DateTime
     */
    private $readAt;

    public function __construct()
    {
        $this->sendAt = new \DateTime();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser2()
    {
        return $this->user2;
    }

    /**
     * @param User $user2
     */
    public function setUser2($user2)
    {
        $this->user2 = $user2;
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

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getSendAt()
    {
        return $this->sendAt;
    }

    /**
     * @param \DateTime $sendAt
     */
    public function setSendAt($sendAt)
    {
        $this->sendAt = $sendAt;
    }

    /**
     * @return bool
     */
    public function isRead()
    {
        return !is_null($this->readAt) ? true : false;
    }

    public function markAsRead()
    {
        $this->readAt = new \DateTime();
    }

    public function markAsUnread()
    {
        $this->readAt = null;
    }
}

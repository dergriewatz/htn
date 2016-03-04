<?php

namespace AppBundle\Entity;

interface UserInterface
{
    /** @return string */
    public function getId();

    /** @return string */
    public function getUsername();

    /** @return string */
    public function getSlug();

    /** @return string */
    public function getPlainPassword();

    /** @return string */
    public function getEmail();

    /** @return string */
    public function getGender();

    /** @return string */
    public function getHomepage();

    /** @return \DateTime */
    public function getBirthday();

    /** @return string */
    public function getAvatar();

    /** @return bool */
    public function isActive();

    /** @return \DateTime */
    public function getLastLogin();
}

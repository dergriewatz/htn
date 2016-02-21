<?php

namespace AppBundle\Utils;

class Slugger
{
    /**
     * @param $string
     * @return string
     */
    public function slugify($string)
    {
        return preg_replace(
            '/[^a-z0-9]/',
            '-',
            strtolower(trim(strip_tags($string)))
        );
    }
}

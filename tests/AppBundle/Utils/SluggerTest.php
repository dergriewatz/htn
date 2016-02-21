<?php

namespace Test\AppBundle\Service;

use AppBundle\Utils\Slugger;
use Prophecy\Argument;
use Tests\AppBundle\IntegrationWebTestCase;

class SluggerTests extends IntegrationWebTestCase
{
    public function dataProviderTestSlugify()
    {
        return [
            ['foo', 'foo'],
            ['FOO', 'foo'],
            ['FöO', 'f--o'],
            ['f68767o!"§$%&/()=o', 'f68767o-----------o'],
        ];
    }

    /**
     * @dataProvider dataProviderTestSlugify
     * @param $string
     * @param $expected
     */
    public function testSlugify($string, $expected)
    {
        $slugger = new Slugger();

        $this->assertSame($expected, $slugger->slugify($string));
    }
}

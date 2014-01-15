<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 15.01.14
 * Time: 12:06
 */

namespace Ahonymous\Bundle\BlogBundle\Tests\Twig;

use Ahonymous\Bundle\BlogBundle\Twig\BlogExtension;

class BlogExtensionsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provider
     */
    public function testEndsWord($equality, $string, $length)
    {
        $checker = new BlogExtension();
        $result = $checker->endsWord($string, $length);

        $this->assertEquals($equality, $result);
    }

    public function provider()
    {
        return array(
            array('<p>qwerty...</p>', '<p>qwerty 12345</p>', 9),
            array('<p>qwerty...</p>', '<p>qwerty 12345</p>', 10),
            array(
                '<article>История о том,...</article>',
                '<article>История о том, как возле столовой появились загадочные розовые следы с шестью пальцами, и почему это случилось. </article>',
                30
            )
        );
    }
}
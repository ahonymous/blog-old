<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.01.14
 * Time: 9:13
 */

namespace Ahonymous\Bundle\BlogBundle\Twig;

//use CG\Core\ClassUtils;

class BlogExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'endsWord' => new \Twig_Filter_Method($this, 'endsWord'),
            'nl2p' => new \Twig_Filter_Method($this, 'nl2p')
        );
    }

    public function nl2p($str, $attribute = null)
    {
        $back_str = '';

        foreach (explode("\n", $str) as $pTag) {
            if(trim($pTag)) {
                $back_str .= "<p class=".$attribute.">$pTag</p>";
            }
        }

        return $back_str;
    }

    public function endsWord($str, $length = 40)
    {
        if (strlen($str) < $length){
            return $str;
        }

        if (!is_null($str)){
            $clause = explode(' ', $str);
            $come = $clause[0];
            $i = 0;
            while (strlen($come) < $length-3)
            {
                $come .= ' ' . $clause[$i++];
            }

            return $come . '...';
        }
    }

    public function getName()
    {
        return 'blog_extension';
    }
}
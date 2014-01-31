<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 07.01.14
 * Time: 9:13
 */

namespace Ahonymous\Bundle\BlogBundle\Twig;

class BlogExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'endsWord' => new \Twig_Filter_Method($this, 'endsWord'),
            'nl2p' => new \Twig_Filter_Method($this, 'nl2p'),
            'sizer' => new \Twig_Filter_Method($this, 'sizer')
        );
    }

    public function sizer($counts, $sum, $size = 14)
    {
        if ($counts != 0) {
            $size += ($counts/ $sum)*100;
        }

        return $size;
    }

    public function nl2p($str, $attribute = null)
    {
        $back_str = '';

        foreach (explode("\n", $str) as $pTag) {
            if (trim($pTag)) {
                $back_str .= "<p class=".$attribute.">".$pTag."</p>";
            }
        }

        return $back_str;
    }

    public function endsWord($str, $length = 40)
    {
        if (strlen($str) < $length) {
            return $str;
        } else {
            $close = explode(' ', $str);
            $come = '';
            $i = 0;
            while (strlen($come) < $length) {
                $come .= ' ' . $close[$i++];
            }
            $come .= '...';

            $tag = $this->closeHTML($come);
            $come .= (!is_null($tag)) ? $tag : null;

            return trim($come);
        }
    }

    public function closeHTML($preView = null)
    {
        $lastTag = '';
        preg_match_all('#<[a-zA-Z0-9="\' ]{1,}[^>]*>#Usi', $preView, $m);
        preg_match_all('#</[a-zA-Z0-9="\' ]{1,}[^>]*>#Usi', $preView, $m2);
        foreach ($m2[0] as $k => $v) {
            $m2[0][$k] = str_replace('/', '', $v);
        }

        foreach (array_reverse(array_diff($m[0], $m2[0])) as $v) {
            $v = preg_replace("#(</?\w+)(?:\s(?:[^<>/]|/[^<>])*)?(/?>)#ui", '$1$2', $v);
            $lastTag .= str_replace('<', '</', $v);
        }

        return $lastTag;
    }

    public function getName()
    {
        return 'blog_extension';
    }
}

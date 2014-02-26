<?php

namespace Ahonymous\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AhonymousUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

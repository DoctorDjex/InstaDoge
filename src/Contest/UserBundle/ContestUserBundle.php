<?php

namespace Contest\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ContestUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}

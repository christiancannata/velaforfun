<?php

namespace NewsletterBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class NewsletterBundle extends Bundle
{
    public function getParent()
    {
        return 'IbrowsNewsletterBundle';
    }
}

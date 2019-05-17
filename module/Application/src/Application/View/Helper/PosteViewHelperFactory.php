<?php

namespace Application\View\Helper;

use Zend\View\HelperPluginManager;

class PosteViewHelperFactory {

    public function __invoke(HelperPluginManager $manager)
    {

        /** @var PosteViewHelper $helper */
        $helper = new PosteViewHelper();
        return $helper;
    }
}
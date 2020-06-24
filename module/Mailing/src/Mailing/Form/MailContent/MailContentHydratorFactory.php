<?php

namespace Mailing\Form\MailContent;

use Interop\Container\ContainerInterface;

class MailContentHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MailContentHydrator $hydrator*/
        $hydrator = new MailContentHydrator();
        return $hydrator;
    }
}
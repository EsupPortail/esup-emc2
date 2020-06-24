<?php

namespace Mailing\Form\MailType;

use Interop\Container\ContainerInterface;

class MailTypeHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return MailTypeHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var MailTypeHydrator $hydrator */
        $hydrator = new MailTypeHydrator();
        return $hydrator;
    }
}
<?php

namespace Mailing\Form\MailType;

use Interop\Container\ContainerInterface;

class MailTypeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return MailTypeForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var MailTypeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MailTypeHydrator::class);

        /** @var MailTypeForm $form */
        $form = new MailTypeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}
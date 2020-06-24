<?php

namespace Mailing\Form\MailContent;

use Interop\Container\ContainerInterface;

class MailContentFormFactory {

    /**
     * @param ContainerInterface $container
     * @return MailContentForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var MailContentHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MailContentHydrator::class);

        /** @var MailContentForm $form */
        $form = new MailContentForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}
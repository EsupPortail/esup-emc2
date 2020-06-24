<?php

namespace Mailing\Controller;

use Interop\Container\ContainerInterface;
use Mailing\Form\MailContent\MailContentForm;
use Mailing\Form\MailType\MailTypeForm;
use Mailing\Service\MailType\MailTypeService;

class MailTypeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MailTypeController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MailTypeService $mailTypeService
         */
        $mailTypeService = $container->get(MailTypeService::class);

        /**
         * @var MailContentForm $mailContentForm
         * @var MailTypeForm $mailTypeForm
         */
        $mailContentForm = $container->get('FormElementManager')->get(MailContentForm::class);
        $mailTypeForm = $container->get('FormElementManager')->get(MailTypeForm::class);

        /** @var MailTypeController $controller */
        $controller = new MailTypeController();
        $controller->setMailTypeService($mailTypeService);
        $controller->setMailContentForm($mailContentForm);
        $controller->setMailTypeForm($mailTypeForm);
        return $controller;
    }
}
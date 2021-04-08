<?php

namespace Application\Controller;

use Application\Form\MaitriseNiveau\MaitriseNiveauForm;
use Application\Service\MaitriseNiveau\MaitriseNiveauService;
use Interop\Container\ContainerInterface;

class MaitriseNiveauControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MaitriseNiveauController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MaitriseNiveauService $MaitriseNiveauService
         * @var MaitriseNiveauForm $MaitriseNiveauForm
         */
        $MaitriseNiveauService = $container->get(MaitriseNiveauService::class);
        $MaitriseNiveauForm = $container->get('FormElementManager')->get(MaitriseNiveauForm::class);

        $controller = new MaitriseNiveauController();
        $controller->setMaitriseNiveauService($MaitriseNiveauService);
        $controller->setMaitriseNiveauForm($MaitriseNiveauForm);
        return $controller;
    }
}
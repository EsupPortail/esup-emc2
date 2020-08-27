<?php

namespace Application\Controller;

use Application\Entity\Db\FonctionActivite;
use Application\Form\FonctionActivite\FonctionActiviteForm;
use Application\Form\FonctionDestination\FonctionDestinationForm;
use Application\Service\Fonction\FonctionService;
use Interop\Container\ContainerInterface;

class FonctionControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FonctionController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FonctionService $fonctionService
         */
        $fonctionService = $container->get(FonctionService::class);

        /**
         * @var FonctionActiviteForm $fonctionActiviteForm
         * @var FonctionDestinationForm $fonctionDestinationForm
         */
        $fonctionActiviteForm = $container->get('FormElementManager')->get(FonctionActiviteForm::class);
        $fonctionDestinationForm = $container->get('FormElementManager')->get(FonctionDestinationForm::class);

        /** @var FonctionController $controller */
        $controller = new FonctionController();
        $controller->setFonctionService($fonctionService);
        $controller->setFonctionActiviteForm($fonctionActiviteForm);
        $controller->setFonctionDestinationForm($fonctionDestinationForm);
        return $controller;
    }
}
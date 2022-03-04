<?php

namespace Application\Form\SelectionFicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;

class SelectionFicheMetierFormFactory {

    public function __invoke(ContainerInterface $container) : SelectionFicheMetierForm
    {
        /**
         * @var FicheMetierService $ficheMetierService
         */
        $ficheMetierService = $container->get(FicheMetierService::class);

        $form = new SelectionFicheMetierForm();
        $form->setFicheMetierService($ficheMetierService);
        return $form;
    }
}
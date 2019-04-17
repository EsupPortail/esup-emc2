<?php

namespace Application\Form\FicheMetierType;

use Application\Service\Activite\ActiviteService;
use Zend\Form\FormElementManager;

class ActiviteExistanteFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var ActiviteExistanteHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(ActiviteExistanteHydrator::class);

        /** @var ActiviteService $activiteService */
        $activiteService = $manager->getServiceLocator()->get(ActiviteService::class);

        $form = new ActiviteExistanteForm();
        $form->setActiviteService($activiteService);
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}
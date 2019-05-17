<?php

namespace Application\Form\RessourceRh;

use Application\Service\Fonction\FonctionService;
use Zend\Form\FormElementManager;

class MetierFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var MetierHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(MetierHydrator::class);

        /** @var FonctionService $fonctionService */
        $fonctionService = $manager->getServiceLocator()->get(FonctionService::class);

        $form = new MetierForm();
        $form->setFonctionService($fonctionService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}
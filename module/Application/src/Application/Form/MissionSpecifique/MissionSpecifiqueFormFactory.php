<?php

namespace Application\Form\MissionSpecifique;

use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager;

class MissionSpecifiqueFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MissionSpecifiqueHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(MissionSpecifiqueHydrator::class);

        /**
         * @var MissionSpecifiqueForm $form
         */
        $form = new MissionSpecifiqueForm();
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }
}
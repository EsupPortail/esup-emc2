<?php

namespace Application\Form\Agent;

use Zend\Form\FormElementManager;

class AgentImportFormFactory {

    public function __invoke(FormElementManager $manager)
    {

        /** @var AgentImportForm $form */
        $form = new AgentImportForm();
        $form->setAutocomplete($manager->getServiceLocator()->get('view_renderer')->url('agent/rechercher-individu',[],[], true));
        $form->init();

        return $form;
    }
}
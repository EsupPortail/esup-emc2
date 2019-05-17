<?php

namespace Application\Form\AssocierAgent;

use Application\Service\Agent\AgentServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class AssocierAgentForm extends Form {
    use AgentServiceAwareTrait;

    public function init()
    {
        //Selection FAMILLE
        $this->add([
            'type' => Select::class,
            'name' => 'agent',
            'options' => [
                'label' => "Agent :",
                'value_options' => $this->generateSelectOptions(),
            ],
            'attributes' => [
                'id' => 'agent',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

//        submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Associer un agent',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    private function generateSelectOptions()
    {
        $agents = $this->getAgentService()->getAgents();
        $options = [];
        $options[0] = "Sélectionner un agent ... ";
        foreach ($agents as $agent) {
            $options[$agent->getId()] = $agent->getDenomination(); /* . ' ('.$agent->getNumeroPoste().')';*/
        }
        return $options;

    }
}
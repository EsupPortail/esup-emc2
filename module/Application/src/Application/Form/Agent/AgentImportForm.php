<?php

namespace Application\Form\Agent;

use Application\Form\AutocompleteAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form\Element\Button;
use Zend\Form\Form;

class AgentImportForm extends Form {


    use AutocompleteAwareTrait;

    public function init()
    {
        $sas = new SearchAndSelect('agent');
        $sas->setLabel('Agent provenant d\'Octopus');
        $sas->setAttribute('placeholder','Recherchez un agent');
        $sas->setAttribute('class', 'individu-finder');
        $sas->setLabelOption('disable_html_escape', false);

        $sas->setAutocompleteSource($this->getAutocomplete());
        $this->add($sas);

        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-user-cog"></i> Importer l\'agent',
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
}
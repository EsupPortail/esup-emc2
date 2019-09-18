<?php

namespace Application\Form\FicheMetier;

use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FormationsForm extends Form {
    use FormationServiceAwareTrait;

    public function init()
    {
        // formation
        $this->add([
            'type' => Select::class,
            'name' => 'formations',
            'options' => [
                'label' => "Formations associées :",
                'empty_option' => "Sélectionner la ou les formations ...",
//                'value_options' => $this->getFormationService()->getFormationsAsOptions(),
                'value_options' => $this->getFormationService()->getFormationsThemesAsGroupOptions(),
            ],
            'attributes' => [
                'id' => 'formations',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'          => 'multiple',
            ],
        ]);

        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'formations' => [
                'required' => false,
            ],
        ]));
    }
}
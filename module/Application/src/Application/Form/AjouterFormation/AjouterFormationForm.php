<?php

namespace Application\Form\AjouterFormation;

use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AjouterFormationForm extends Form
{
    use FormationServiceAwareTrait;

    public function init()
    {
        /** SELECT :: FORMATION */
        $this->add([
            'name' => 'formation',
            'type' => Select::class,
            'options' => [
                'label' => 'Formations : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une ou plusieurs formations ... ",
                'value_options' => $this->getFormationService()->getFormationsGroupesAsGroupOptions(),
            ],
            'attributes' => [
                'id'                => 'formation',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'          => 'multiple',
            ]
        ]);

        /** BUTTON :: SUBMIT */
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Ajouter',
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
            'formation'         => [ 'required' => false,  ],
        ]));
    }

}
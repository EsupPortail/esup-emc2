<?php

namespace Application\Form\SpecificitePoste;

use Zend\Form\Element\Button;
use Zend\Form\Form;

class SpecificitePosteForm extends Form {

    public function init()
    {
        // specificite
        $this->add([
            'name' => 'specificite',
            'type' => 'textarea',
            'options' => [
                'label' => 'Spécificité du poste : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control specificite',
            ]
        ]);
        // encadrement
        $this->add([
            'name' => 'encadrement',
            'type' => 'textarea',
            'options' => [
                'label' => 'Encadrement : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control encadrement',
            ]
        ]);
        // relations internes
        $this->add([
            'name' => 'relations_internes',
            'type' => 'textarea',
            'options' => [
                'label' => 'Relations internes à l\'unicaen : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control relations_internes',
            ]
        ]);
        // relation externes
        $this->add([
            'name' => 'relations_externes',
            'type' => 'textarea',
            'options' => [
                'label' => 'Relations externes à l\'unicaen ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control relations_externes',
            ]
        ]);
        // contraintes
        $this->add([
            'name' => 'contraintes',
            'type' => 'textarea',
            'options' => [
                'label' => 'Sujétions ou conditions particulières : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control contraintes',
            ]
        ]);
        // moyens
        $this->add([
            'name' => 'moyens',
            'type' => 'textarea',
            'options' => [
                'label' => 'Les moyens et outils mis à disposition : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control moyens',
            ]
        ]);
        // moyens
        $this->add([
            'name' => 'formations',
            'type' => 'textarea',
            'options' => [
                'label' => 'Formations et qualifications nécessaires : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control formations',
            ]
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
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
    }
}
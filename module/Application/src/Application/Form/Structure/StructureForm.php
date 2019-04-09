<?php

namespace Application\Form\Structure;

use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Form\Element\Date;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class StructureForm extends Form {
    use StructureServiceAwareTrait;

    public function init()
    {
        // libelle court
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_court',
            'options' => [
                'label' => "Libelle court* :",
            ],
            'attributes' => [
                'id' => 'libelle_court',
            ],
        ]);
        // libelle long
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_long',
            'options' => [
                'label' => "Libelle long :",
            ],
            'attributes' => [
                'id' => 'libelle_long',
            ],
        ]);
        // sigle
        $this->add([
            'type' => Text::class,
            'name' => 'sigle',
            'options' => [
                'label' => "Sigle :",
            ],
            'attributes' => [
                'id' => 'sigle',
            ],
        ]);
        // sigle
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de structure* :",
                'value_options' => $this->getStructureService()->getStructureTypeAsOptions(),
            ],
            'attributes' => [
                'id' => 'type',
            ],
        ]);
        // date d'ouverture
        $this->add([
            'type' => Date::class,
            'name' => 'date_ouverture',
            'options' => [
                'label' => "Date ouverture* :",
            ],
            'attributes' => [
                'id' => 'date_ouverture',
            ],
        ]);
        // date de fermeture
        $this->add([
            'type' => Date::class,
            'name' => 'date_fermeture',
            'options' => [
                'label' => "Date de fermeture :",
            ],
            'attributes' => [
                'id' => 'date_fermeture',
            ],
        ]);
        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
            ]
        ]);
        // button
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
            'libelle_court'     => [ 'required' => true,   ],
            'libelle_long'      => [ 'required' => false,  ],
            'sigle'             => [ 'required' => false,  ],
            'type'              => [ 'required' => true,   ],
            'date_ouverture'    => [ 'required' => true,   ],
            'date_fermeture'    => [ 'required' => false,  ],
            'description'       => [ 'required' => false,  ],
        ]));
    }
}
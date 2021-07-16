<?php

namespace Metier\Form\MetierNiveau;

use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Number;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class MetierNiveauForm extends Form {

    public function init()
    {
        $this->add([
            'type' => Hidden::class,
            'name' => 'metier',
        ]);
        $this->add([
            'type' => Number::class,
            'name' => 'borne_inferieure',
            'options' => [
                'label' => "Niveau le plus bas * :",
            ],
            'attributes' => [
                'id' => 'borne_inferieure',
                'min'  => '1',
                'max'  => '5',
                'step' => '1',
            ],
        ]);
        $this->add([
            'type' => Number::class,
            'name' => 'borne_superieure',
            'options' => [
                'label' => "Niveau le plus élévé * :",
            ],
            'attributes' => [
                'id' => 'borne_superieure',
                'min'  => '1',
                'max'  => '5',
                'step' => '1',
            ],
        ]);
        $this->add([
            'type' => Number::class,
            'name' => 'valeur_recommandee',
            'options' => [
                'label' => "Valeur recommandée * :",
            ],
            'attributes' => [
                'id' => 'valeur_recommandee',
                'min'  => '1',
                'max'  => '5',
                'step' => '1',
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
        //button
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
            'metier'                => [ 'required' => true,  ],
            'borne_inferieure'      => [ 'required' => true,  ],
            'borne_superieure'      => [ 'required' => true,  ],
            'valeur_recommandee'    => [ 'required' => true,  ],
            'description'           => [ 'required' => false,  ],
        ]));
    }
}
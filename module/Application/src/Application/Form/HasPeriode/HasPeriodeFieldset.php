<?php

namespace Application\Form\HasPeriode;

use Zend\Form\Element\DateTime;
use Zend\Form\Fieldset;

class HasPeriodeFieldset extends Fieldset {

    const format = 'd/m/Y';

    public function init()
    {
        //DEBUT (DATE)
        $this->add([
            'name' => 'date_debut',
            'type' => DateTime::class,
            'options' => [
                'label' => 'Date de début : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_debut',
            ]
        ]);
        //FIN (DATE)
        $this->add([
            'name' => 'date_fin',
            'type' => DateTime::class,
            'options' => [
                'label' => 'Date de fin : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_fin',
            ]
        ]);
    }
}
<?php

namespace Application\Form\HasPeriode;

use Laminas\Form\Element\Date;
use Laminas\Form\Fieldset;

class HasPeriodeFieldset extends Fieldset
{

    //const format = 'd/m/Y';
    const format = 'Y-m-d';

    public function init(): void
    {
        //DEBUT (DATE)
        $this->add([
            'name' => 'date_debut',
            'type' => Date::class,
            'options' => [
                'label' => 'Date de début : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => HasPeriodeFieldset::format,
            ],
            'attributes' => [
                'id' => 'date_debut',
            ]
        ]);
        //FIN (DATE)
        $this->add([
            'name' => 'date_fin',
            'type' => Date::class,
            'options' => [
                'label' => 'Date de fin : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'format' => HasPeriodeFieldset::format,
            ],
            'attributes' => [
                'id' => 'date_fin',
            ]
        ]);
    }
}
<?php

namespace FicheMetier\Form\SelectionFicheMetier;

use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionFicheMetierForm extends Form
{
    use FicheMetierServiceAwareTrait;

    public function init()
    {
        $this->add([
            'type' => Select::class,
            'name' => 'fiche-metier',
            'options' => [
                'label' => "Fiche métier * :",
                'empty_option' => "Sélectionner une fiche métier ...",
                'value_options' => $this->getFicheMetierService()->getFichesMetiersAsOptionGroup(),
            ],
            'attributes' => [
                'id' => 'fiche-metier',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);

        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-clone"></i> Sélectionner',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputFilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'fiche-metier' => ['required' => true,],
        ]));
    }
}
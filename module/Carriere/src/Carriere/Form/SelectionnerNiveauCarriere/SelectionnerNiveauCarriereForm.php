<?php

namespace Carriere\Form\SelectionnerNiveauCarriere;

use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionnerNiveauCarriereForm extends Form
{
    use NiveauServiceAwareTrait;

    public function init(): void
    {
        //select
        $this->add([
            'type' => Select::class,
            'name' => 'niveau_carriere',
            'options' => [
                'label' => "Niveau de carrière <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => 'Sélectionner un niveau ...',
                'value_options' => $this->getNiveauService()->getNiveauxAsOptions(),
            ],
            'attributes' => [
                'id' => 'niveau_carriere',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer ',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'niveau_carriere' => [ 'required' => true,  ],
        ]));
    }

}

<?php

namespace Formation\Form\PlanDeFormation;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Date;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class PlanDeFormationForm extends Form
{

    public function init(): void
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => "libelle",
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //description
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Description :",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => "type2",
            ],
        ]);
        //dates
        $this->add([
            'name' => 'date_debut',
            'type' => Date::class,
            'options' => [
                'label' => "Date de début <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
//                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_debut',
            ]
        ]);
        $this->add([
            'name' => 'date_fin',
            'type' => Date::class,
            'options' => [
                'label' => "Date de fin :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [
                    'class' => 'control-label',
                ],
//                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id'                => 'date_fin',
            ]
        ]);
        //submit
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

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'               => [ 'required' => true,  ],
            'description'           => [ 'required' => false, ],
            'date_debut'            => [ 'required' => true,  ],
            'date_fin'              => [ 'required' => false, ],
        ]));
    }
}
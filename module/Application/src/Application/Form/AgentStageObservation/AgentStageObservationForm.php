<?php

namespace Application\Form\AgentStageObservation;

use Application\Form\HasPeriode\HasPeriodeFieldset;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenEtat\Form\EtatFieldset\EtatFieldset;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AgentStageObservationForm extends Form {
    use StructureServiceAwareTrait;
    use MetierServiceAwareTrait;
    use EtatServiceAwareTrait;

    public function init()
    {
        //--agent déduiu de l'action
        $this->add([
            'type' => Select::class,
            'name' => 'structure',
            'options' => [
                'label' => "Structure du stage :",
                'empty_option' => 'Sélectionner une structure ...',
                'value_options' => $this->getStructureService()->getStructuresAsOptions(),
            ],
            'attributes' => [
                'id'                => 'structure',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        $this->add([
            'type' => Select::class,
            'name' => 'metier',
            'options' => [
                'label' => "Métier observé :",
                'empty_option' => 'Sélectionner un métier ...',
                'value_options' => $this->getMetierService()->getMetiersTypesAsMultiOptions(),
            ],
            'attributes' => [
                'id'                => 'metier',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        // description
        $this->add([
            'name' => 'HasPeriode',
            'type' => HasPeriodeFieldset::class,
            'attributes' => [
                'id' => 'periode',
            ]
        ]);
        // etat
        $this->add([
            'name' => 'etat',
            'type' => EtatFieldset::class,
            'attributes' => [
                'id' => 'etat',
            ]
        ]);

        // complement
        $this->add([
            'name' => 'complement',
            'type' => 'textarea',
            'options' => [
                'label' => 'Complément : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
                'id' => 'description',
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

        //inputFIlter
        $this->setInputFilter((new Factory())->createInputFilter([
            'structure'         => [ 'required' => false,  ],
            'metier'            => [ 'required' => true,  ],
            'HasPeriode'        => [ 'required' => false, ],
            'etat'              => [ 'required' => false, ],
            'complement'        => [ 'required' => false, ],
        ]));
    }
}

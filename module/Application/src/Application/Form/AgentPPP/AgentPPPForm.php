<?php

namespace Application\Form\AgentPPP;

use Application\Form\HasPeriode\HasPeriodeFieldset;
use UnicaenEtat\Form\EtatFieldset\EtatFieldset;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class AgentPPPForm extends Form {
    use EtatServiceAwareTrait;

    public function init()
    {
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de projet professionnel personnel * :",
                'empty_option' => 'Sélectionner unu type ...',
                'value_options' => [
                    //todo fonciton dans service voir entités
                    'Congé de formation' => 'Congé de formation',
                    'V.A.E.' => 'V.A.E.',
                    'Bilan de compétences' => 'Bilan de compétences',
                    'Formation en e-learning' => 'Formation en e-learning',
                    'Compte personnel de formation' => 'Compte personnel de formation',
                ]
            ],
            'attributes' => [
                'id'                => 'type',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //--agent déduiu de l'action
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé du projet professionnel personnel  : ",
                'label_attributes' => [
                    'class' => 'required',
                ],
            ],
            'attributes' => [
                'id' => 'libelle',
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
        /// etat
        $this->add([
            'name' => 'etat',
            'type' => EtatFieldset::class,
            'attributes' => [
                'id' => 'etat',
            ]
        ]);

        // Utilisation du cpf
        $this->add([
            'type' => Number::class,
            'name' => 'cpf',
            'options' => [
                'label' => "Utilisation du CPF (en heure)  :",
            ],
            'attributes' => [
                'id' => 'cpf',
            ],
        ]);
        // Cout de la formation
        $this->add([
            'type' => Number::class,
            'name' => 'cout',
            'options' => [
                'label' => "Coût de la formation (en euro)  :",
            ],
            'attributes' => [
                'id' => 'cout',
            ],
        ]);
        // Prise en charge de la formation
        $this->add([
            'type' => Number::class,
            'name' => 'priseencharge',
            'options' => [
                'label' => "Prise en charge de la formation (en euro)  :",
            ],
            'attributes' => [
                'id' => 'priseencharge',
            ],
        ]);
        //libelle de l'orgranisme
        $this->add([
            'type' => Text::class,
            'name' => 'organisme',
            'options' => [
                'label' => "Organisme de formation  : ",
            ],
            'attributes' => [
                'id' => 'organisme',
            ],
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
            'type'              => [ 'required' => true,  ],
            'libelle'           => [ 'required' => true,  ],
            'HasPeriode'        => [ 'required' => false, ],
            'etat'              => [ 'required' => false, ],
            'cpf'               => [ 'required' => false, ],
            'cout'              => [ 'required' => false, ],
            'priseencharge'     => [ 'required' => false, ],
            'organisme'         => [ 'required' => false, ],
            'complement'        => [ 'required' => false, ],
        ]));
    }
}
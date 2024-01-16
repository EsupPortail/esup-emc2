<?php

namespace Formation\Form\Inscription;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenApp\Form\Element\SearchAndSelect;

class InscriptionForm extends Form {

    public ?string $agentUrl = null;
    public ?string $sessionUrl = null;
    public ?string $stagiaireUrl = null;


    public function init(): void
    {
        // session
        $session = new SearchAndSelect('session', ['label' => "Session de formation * :"]);
        $session
            ->setAutocompleteSource($this->sessionUrl)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'session',
                'placeholder' => "Session de formation ...",
            ]);
        $this->add($session);
        // type
        $this->add([
            'type' => Radio::class,
            'name' => 'type',
            'options' => [
                'label' => "Type d'inscription  <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    'agent' => "Agent",
                    'stagiaire' => "Stagiaire externe",
                ],
            ],
            'attributes' => [
                'id' => 'type',
            ],
        ]);
        // agent
        $agent = new SearchAndSelect('agent', ['label' => "Agent associé·e à l'inscription * :"]);
        $agent
            ->setAutocompleteSource($this->agentUrl)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'agent',
                'placeholder' => "Dénomination de l'agent ...",
            ]);
        $this->add($agent);
        // stagiaire
        $agent = new SearchAndSelect('stagiaire', ['label' => "Stagiaire associé·e à l'inscription * :"]);
        $agent
            ->setAutocompleteSource($this->stagiaireUrl)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'stagiaire',
                'placeholder' => "Dénomination du stagiaire ...",
            ]);
        $this->add($agent);
        // button
        $this->add([
            'type' => Button::class,
                'name' => 'valider',
                'options' => [
                    'label' => "<i class='fas fa-save'></i> Enregistrer l'inscription",
                    'label_options' => [
                        'disable_html_escape' => true,
                    ],
                ],
                'attributes' => [
                    'type' => 'submit',
                    'class' => 'btn btn-primary',
                    'id' => 'valider',
                ],
        ]);

        //inputFIlter
        $this->setInputFilter((new Factory())->createInputFilter([
            'session'         => [ 'required' => true,  ],
            'type'            => [ 'required' => true,  ],
            'agent'           => [ 'required' => false, ],
            'stagiaire'       => [ 'required' => false, ],
        ]));
    }
}
<?php

namespace Application\Form\AgentTutorat;

use Application\Form\HasPeriode\HasPeriodeFieldset;
use Application\Service\Agent\AgentServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Metier\Service\Metier\MetierServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenEtat\Form\EtatFieldset\EtatFieldset;

class AgentTutoratForm extends Form
{
    use AgentServiceAwareTrait;
    use MetierServiceAwareTrait;

    public string $urlAgent;

    public function init(): void
    {
        //--agent déduiu de l'action
        //Cible
        $cible = new SearchAndSelect('cible', ['label' => "Agent :"]);
        $cible
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired()
            ->setAttributes([
                'id' => 'cible',
                'placeholder' => "Agent encadré par le tutorat ...",
            ]);
        $this->add($cible);
        $this->add([
            'type' => Select::class,
            'name' => 'metier',
            'options' => [
                'label' => "Métier observé :",
                'empty_option' => 'Sélectionner un métier ...',
                'value_options' => $this->getMetierService()->getMetiersTypesAsMultiOptions(),
            ],
            'attributes' => [
                'id' => 'metier',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
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
        //formation
        $this->add([
            'type' => Select::class,
            'name' => 'formation',
            'options' => [
                'label' => "L'agent a été formé pour faire ce tutorat",
                'value_options' => [
                    null => "Information inconnue",
                    true => "Oui, iel a été·e formé",
                    false => "Non, iel n'a pas été·e formé",
                ],
            ],
            'attributes' => [
                'id' => 'formation',
            ],
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
            'cible' => ['required' => false,],
            'metier' => ['required' => true,],
            'HasPeriode' => ['required' => false,],
            'etat' => ['required' => false,],
            'complement' => ['required' => false,],
            'formation' => ['required' => false,],
        ]));
    }
}
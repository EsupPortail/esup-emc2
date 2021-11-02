<?php

namespace Application\Form\AgentAccompagnement;

use Application\Form\HasPeriode\HasPeriodeFieldset;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Corps\CorpsServiceAwareTrait;
use Application\Service\Correspondance\CorrespondanceServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use UnicaenEtat\Form\EtatFieldset\EtatFieldset;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AgentAccompagnementForm extends Form
{
    use AgentServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use CorpsServiceAwareTrait;
    use EtatServiceAwareTrait;

    public $urlAgent;

    public function init()
    {
        //--agent déduiu de l'action
        //Cible
        $cible = new SearchAndSelect('cible', ['label' => "Agent :"]);
        $cible
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'cible',
                'placeholder' => "Agent encadré par le tutorat ...",
            ]);
        $this->add($cible);
        $this->add([
            'type' => Select::class,
            'name' => 'bap',
            'options' => [
                'label' => "Bap concerné :",
                'empty_option' => 'Sélectionner une bap ...',
                'value_options' => $this->getCorrespondanceService()->getCorrespondancesAsOptions(),
            ],
            'attributes' => [
                'id' => 'bap',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        $this->add([
            'type' => Select::class,
            'name' => 'corps',
            'options' => [
                'label' => "Corps concerné :",
                'empty_option' => 'Sélectionner un corps ...',
                'value_options' => $this->getCorpsService()->getCorpsAsOptions(),
            ],
            'attributes' => [
                'id' => 'corps',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        // periode
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
        //resultat
        $this->add([
            'type' => Select::class,
            'name' => 'resultat',
            'options' => [
                'label' => "L'agent accompagné a obtenu le concours",
                'value_options' => [
                    null => "Information inconnue",
                    true => "Oui, iel a obtenu le concours",
                    false => "Non, iel n'a pas obtenu le concours",
                ],
            ],
            'attributes' => [
                'id' => 'resultat',
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
            'bap' => ['required' => true,],
            'corps' => ['required' => true,],
            'HasPeriode' => ['required' => false,],
            'etat' => ['required' => false,],
            'complement' => ['required' => false,],
            'resultat' => ['required' => false,],
        ]));
    }
}
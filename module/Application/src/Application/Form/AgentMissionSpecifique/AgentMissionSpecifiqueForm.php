<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Form\HasPeriode\HasPeriodeFieldset;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Number;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class AgentMissionSpecifiqueForm extends Form {
    use MissionSpecifiqueServiceAwareTrait;

    /** @var string */
    private $urlAgent;

    /**
     * @param string $urlAgent
     * @return AgentMissionSpecifiqueForm
     */
    public function setUrlAgent(string $urlAgent): AgentMissionSpecifiqueForm
    {
        $this->urlAgent = $urlAgent;
        return $this;
    }

    /** @var string */
    private $urlStructure;

    /**
     * @param string $url
     * @return AgentMissionSpecifiqueForm
     */
    public function setUrlStructure($url)
    {
        $this->urlStructure = $url;
        return $this;
    }

    public function init(): void
    {
        //Mission
        $this->add([
            'type' => Select::class,
            'name' => 'mission',
            'options' => [
                'label' => "Mission * :",
                'empty_option' => 'Sélectionner la mission à affecter ...',
                'value_options' => $this->getMissionSpecifiqueService()->getMisssionsSpecifiquesAsGroupOptions(),
            ],
            'attributes' => [
                'id' => 'mission',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        //Agent
        $agent = new SearchAndSelect('agent', ['label' => "Agent * :"]);
        $agent
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'agent',
                'placeholder' => "Agent effectuant la mission ...",
            ]);
        $this->add($agent);

        // structure
        $structure = new SearchAndSelect('structure', ['label' => "Service/composante/direction d'affectation * :"]);
        $structure
            ->setAutocompleteSource($this->urlStructure)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'structure',
                'placeholder' => "Nom de la structure ...",
            ]);
        $this->add($structure);

        // periode
        $this->add([
            'name' => 'HasPeriode',
            'type' => HasPeriodeFieldset::class,
            'attributes' => [
                'id' => 'periode',
            ]
        ]);
        //Decharge
        $this->add([
            'type' => Number::class,
            'name' => 'decharge',
            'options' => [
                'label' => "Volume horaire associé à la mission pour une année complète  :",
            ],
            'attributes' => [
                'id' => 'decharge',
            ],
        ]);

        //Submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer affectation',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'agent'             => [ 'required' => true,  ],
            'mission'           => [ 'required' => true,  ],
            'structure'         => [ 'required' => false, ],
            'HasPeriode'        => [ 'required' => false,
//                'HasPeriode#date_debut' => ['required' => true],
//                'HasPeriode#date_fin' => ['required' => false],
            ],
            'decharge'          => [ 'required' => false, ],
        ]));
    }
}
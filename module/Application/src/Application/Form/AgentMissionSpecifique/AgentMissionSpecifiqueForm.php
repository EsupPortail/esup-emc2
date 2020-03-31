<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Entity\Db\Structure;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Form\Element\Date;
use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form\Element\Button;
use Zend\Form\Element\Number;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AgentMissionSpecifiqueForm extends Form {
    use AgentServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    use StructureServiceAwareTrait;

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

    public function init()
    {
        //Agent
//        $this->add([
//            'type' => Select::class,
//            'name' => 'agent',
//            'options' => [
//                'label' => "Agent* :",
//                'empty_option' => 'Sélectionner l\'agent à affecter ...',
//                'value_options' => $this->getAgentService()->getAgentsAsOption(),
//            ],
//            'attributes' => [
//                'id' => 'agent',
//                'class'             => 'bootstrap-selectpicker show-tick',
//                'data-live-search'  => 'true',
//            ],
//        ]);

        //Mission
        $this->add([
            'type' => Select::class,
            'name' => 'mission',
            'options' => [
                'label' => "Mission* :",
                'empty_option' => 'Sélectionner la mission à affecter ...',
                'value_options' => $this->getRessourceRhService()->getMisssionsSpecifiquesAsGroupOptions(),
            ],
            'attributes' => [
                'id' => 'mission',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        // structure
        $structure = new SearchAndSelect('structure', ['label' => "Service/composante/direction d'affectation * :"]);
        $structure
            ->setAutocompleteSource($this->urlStructure)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'structure',
                'placeholder' => "Nom de la structure...",
            ]);
        $this->add($structure);

        //Debut
        $this->add([
            'type' => Date::class,
            'name' => 'debut',
            'options' => [
                'label' => "Date de début* :",
            ],
            'attributes' => [
                'id' => 'debut',
            ],
        ]);

        //Fin
        $this->add([
            'type' => Date::class,
            'name' => 'fin',
            'options' => [
                'label' => "Date de fin :",
            ],
            'attributes' => [
                'id' => 'fin',
            ],
        ]);
        //Decharge
        $this->add([
            'type' => Number::class,
            'name' => 'decharge',
            'options' => [
                'label' => "Décharge associée à la mission* :",
            ],
            'attributes' => [
                'id' => 'decharge',
            ],
        ]);

        //Submit
        // button
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
//            'agent'             => [ 'required' => true,  ],
            'mission'           => [ 'required' => true,  ],
            'structure'         => [ 'required' => false, ],
            'debut'             => [ 'required' => true,  ],
            'fin'               => [ 'required' => false, ],
            'decharge'               => [ 'required' => false, ],
        ]));
    }

    /**
     * @param Structure $structure
     * @param bool $sousstructure
     * @return  AgentMissionSpecifiqueForm
     */
    public function reinitWithStructure($structure, $sousstructure = false)
    {
        //agent
        $agents = $this->getAgentService()->getAgentsByStructure($structure, $sousstructure);
        $agentOptions = [];
        foreach ($agents as $agent) {
            $agentOptions[$agent->getId()] = $agent->getDenomination();
        }
        /** @var Select $this->get('agent') */
        $this->get('agent')->setValueOptions($agentOptions);

        //structure
        $structureOptions = [];
        $structureOptions[$structure->getId()] = $structure->getLibelleCourt();

        if ($sousstructure === true) {
            $sousstructures = $this->getStructureService()->getSousStructures($structure);
            foreach ($sousstructures as $sous) {
                $structureOptions[$sous->getId()] = $sous->getLibelleCourt();
            }
        }
        $this->get('structure')->setValueOptions($structureOptions);
        $this->get('structure')->setEmptyOption(null);


        return $this;
    }
}
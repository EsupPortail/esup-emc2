<?php

namespace EntretienProfessionnel\Form\AgentForceSansObligation;

use Agent\Service\Agent\AgentServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationServiceAwareTrait;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\Form\Element\SearchAndSelect;

class AgentForceSansObligationForm extends Form
{
    use CampagneServiceAwareTrait;
    use AgentServiceAwareTrait;
    use StructureServiceAwareTrait;
    use AgentForceSansObligationServiceAwareTrait;

    private ?string $urlAgent = null;

    public function setUrlAgent(string $url): void
    {
        $this->urlAgent = $url;
    }


    private ?string $urlStructure = null;

    public function setUrlStructure(string $url): void
    {
        $this->urlStructure = $url;
    }

    public function init(): void
    {
        //agent
        //Agent
        $agent = new SearchAndSelect('agentsearch', ['label' => "Agent <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :"]);
        $agent
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired()
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'agentsearch',
                'placeholder' => "Nom de l'agent ...",
            ]);
        $this->add($agent);
        //campagne
        $this->add([
            'type' => Select::class,
            'name' => 'campagne',
            'options' => [
                'label' => 'Campagne  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => "Sélectionner une campagne ...",
                'value_options' =>  $this->getCampagneService()->getCampagnesAsOptions(),
            ],
            'attributes' => [
                'id' => 'campagne',
            ],
        ]);
        //structure
        $structure = new SearchAndSelect('structuresearch', ['label' => "Structure <span class='icon icon-information' title='Si une structure est saisie alors l&apos;exception sera limitée au périmètre de cette structure. L&apos;agent·e ne sera pas soumis·e à cette exception dans ses autres structures d&apos;affectation. Peut être utilisé pour restreindre un agent à faire son entretien dans une de ses structures d&apos;affectation.'></span>:"]);
        $structure
            ->setAutocompleteSource($this->urlStructure)
            ->setSelectionRequired()
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'structuresearch',
                'placeholder' => "Nom de la structure ...",
            ]);
        $this->add($structure);
        //type
        $this->add([
            'type' => Radio::class,
            'name' => 'type',
            'options' => [
                'label' => "Type d'exception <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'value_options' => AgentForceSansObligation::FORCAGE_ARRAY,
            ],
            'attributes' => [
                'id' => 'type',
            ],
        ]);
        //raison
        $this->add([
            'type' => Textarea::class,
            'name' => 'raison',
            'options' => [
                'label' => "Raison de l'exception",
            ],
            'attributes' => [
                'id' => 'raison',
                'class' => 'tinymce',
            ],
        ]);
        //button
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
            'agentsearch' => ['required' => true,],
            'structuresearch' => ['required' => false,],
            'campagne' => ['required' => true,],
            'type' => ['required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Une exception existe déjà pour cet·te agent·e et campagne sur la structure sélectionnée.",
                        ],
                        'callback' => function ($value, $context = []) {
                            $agent = $this->getAgentService()->getAgent($context['agentsearch']['id']);
                            $structure = $this->getStructureService()->getStructure($context['structuresearch']['id']);
                            $campagne = $this->getCampagneService()->getCampagne($context['campagne']);
                            $has = $this->getAgentForceSansObligationService()->getAgentsForcesSansObligationByCampagneAndAgentAndStructure(
                                $campagne, $agent, $structure
                            );
                            if (!empty($has)) {
                                if (count($has) === 1 AND current($has)->getId() === $this->getObject()->getId()) return true;
                                return false;
                            }
                            return true;
                        },
                    ],
                ]],],
            'raison' => ['required' => false,],
        ]));
    }

}
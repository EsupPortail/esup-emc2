<?php

namespace EntretienProfessionnel\Form\AgentForceSansObligation;

use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenApp\Form\Element\SearchAndSelect;

class AgentForceSansObligationForm extends Form
{
    use CampagneServiceAwareTrait;

    private ?string $urlAgent = null;

    public function setUrlAgent(string $url): void
    {
        $this->urlAgent = $url;
    }

    public function init(): void
    {
        //agent
        //Agent
        $agent = new SearchAndSelect('agentsearch', ['label' => "Agent <span class='icon obligatoire text-danger' title='Champ obligatoire'></span> :"]);
        $agent
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
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
        $this->add([
            'type' => Radio::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de forçage  <span class='icon obligatoire' title='Champ obligatoire'></span> :",
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
                'label' => "Raison de la sortie de la campagne d'entretien professionnel ",
            ],
            'attributes' => [
                'id' => 'raison',
                'class' => 'type2',
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
            'campagne' => ['required' => true,],
            'raison' => ['required' => false,],
        ]));
    }

}
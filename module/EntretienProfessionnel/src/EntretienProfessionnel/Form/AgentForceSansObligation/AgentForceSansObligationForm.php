<?php

namespace EntretienProfessionnel\Form\AgentForceSansObligation;

use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Form\Element\Button;
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
        $agent = new SearchAndSelect('agent', ['label' => "Agent <span class='icon obligatoire text-danger' title='Champ obligatoire'></span> :"]);
        $agent
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'agent',
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
            'agent' => ['required' => true,],
            'campagne' => ['required' => true,],
            'raison' => ['required' => false,],
        ]));
    }

}
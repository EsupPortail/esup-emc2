<?php

namespace Formation\Form\Abonnement;

use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenApp\Form\Element\SearchAndSelect;

class AbonnementForm extends Form {
    use FormationServiceAwareTrait;

    private ?string $urlAgent = null;
    public function setUrlAgent(string $url) { $this->urlAgent = $url; }

    public function init(): void
    {
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

        //formation
        $this->add([
            'type' => Select::class,
            'name' => 'formation',
            'options' => [
                'label' => "Formation * :",
                'empty_option' => 'Sélectionner une formation ...',
                'value_options' => $this->getFormationService()->getFormationsAsOptions(),
            ],
            'attributes' => [
                'id' => 'formation',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        //Button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'activité',
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
            'formation' => ['required' => true,],
        ]));
    }
}
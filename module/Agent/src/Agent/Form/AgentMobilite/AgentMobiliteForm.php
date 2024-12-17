<?php

namespace Agent\Form\AgentMobilite;

use Application\Service\Agent\AgentServiceAwareTrait;
use Carriere\Service\Mobilite\MobiliteServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenApp\Form\Element\SearchAndSelect;

class AgentMobiliteForm extends Form
{
    use AgentServiceAwareTrait;
    use MobiliteServiceAwareTrait;

    public string $urlAgent;

    public function init(): void
    {
        //--agent déduiu de l'action
        //Cible
        $cible = new SearchAndSelect('agent', ['label' => "Agent·e :"]);
        $cible
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'agent',
                'placeholder' => "Agent·e concerné·e ...",
            ]);
        $this->add($cible);

        $this->add([
            'type' => Select::class,
            'name' => 'mobilite',
            'options' => [
                'label' => "Mobilité souhaitée <span title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => 'Sélectionner une mobilité  ...',
                'value_options' => $this->getMobiliteService()->getMobiliteAsOptions(),
            ],
            'attributes' => [
                'id' => 'mobilite',
                'class' => 'bootstrap-selectpicker show-tick',
                'data-live-search' => 'true',
            ],
        ]);
        $this->add([
            'name' => 'commentaire',
            'type' => 'textarea',
            'options' => [
                'label' => 'Commentaire : ',
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
            'agent' => ['required' => true,],
            'mobilite' => ['required' => true,],
            'commentaire' => ['required' => false,],
        ]));
    }
}
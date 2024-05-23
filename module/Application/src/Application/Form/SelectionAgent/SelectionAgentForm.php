<?php

namespace Application\Form\SelectionAgent;

use UnicaenApp\Form\Element\SearchAndSelect;
use Laminas\Form\Element\Button;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionAgentForm extends Form {

    /** @var string */
    private $urlAgent;

    /**
     * @param string $urlAgent
     * @return SelectionAgentForm
     */
    public function setUrlAgent(string $urlAgent)
    {
        $this->urlAgent = $urlAgent;
        return $this;
    }

    public function init(): void
    {
        //Agent
        $agent = new SearchAndSelect('agent-sas', ['label' => "Agent·e * :"]);
        $agent
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setAttributes([
                'id' => 'agent-sas',
                'placeholder' => "Dénomination de l'agent·e ...",
            ]);
        $this->add($agent);

        //Submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer ',
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
            'agent-sas'             => [ 'required' => true,  ],
        ]));
    }
}
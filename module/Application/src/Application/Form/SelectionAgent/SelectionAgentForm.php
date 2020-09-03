<?php

namespace Application\Form\SelectionAgent;

use UnicaenApp\Form\Element\SearchAndSelect;
use Zend\Form\Element\Button;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

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

    public function init()
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
        ]));
    }
}
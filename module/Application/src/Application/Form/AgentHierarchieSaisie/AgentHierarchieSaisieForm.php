<?php

namespace Application\Form\AgentHierarchieSaisie;

use Laminas\Form\Element\Button;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenApp\Form\Element\SearchAndSelect;

class AgentHierarchieSaisieForm extends Form {

    private ?string $urlAgent = null;

    public function setUrlAgent(string $url)
    {
        $this->urlAgent = $url;
    }

    public function init(): void
    {
        //agent
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

        //sup1
        $sup1 = new SearchAndSelect('superieur1', ['label' => "Supérieur·e hiérachique 1 :"]);
        $sup1
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'superieur1',
                'placeholder' => "Nom du supéreieur·e ...",
            ]);
        $this->add($sup1);
        //sup2
        $sup2 = new SearchAndSelect('superieur2', ['label' => "Supérieur·e hiérachique 2 :"]);
        $sup2
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'superieur2',
                'placeholder' => "Nom du supéreieur·e ...",
            ]);
        $this->add($sup2);
        //sup3
        $sup3 = new SearchAndSelect('superieur3', ['label' => "Supérieur·e hiérachique 3 :"]);
        $sup3
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'superieur3',
                'placeholder' => "Nom du supéreieur·e ...",
            ]);
        $this->add($sup3);

        //aut1
        $aut1 = new SearchAndSelect('autorite1', ['label' => "Autorité hiérarchique 1 :"]);
        $aut1
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'autorite1',
                'placeholder' => "Nom de l'autorité ...",
            ]);
        $this->add($aut1);
        //aut2
        $aut2 = new SearchAndSelect('autorite2', ['label' => "Autorité hiérarchique 2 :"]);
        $aut2
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'autorite2',
                'placeholder' => "Nom de l'autorité ...",
            ]);
        $this->add($aut2);
        //aut3
        $aut3 = new SearchAndSelect('autorite3', ['label' => "Autorité hiérarchique 3 :"]);
        $aut3
            ->setAutocompleteSource($this->urlAgent)
            ->setSelectionRequired(true)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'autorite3',
                'placeholder' => "Nom de l'autorité ...",
            ]);
        $this->add($aut3);

        //bouton
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

        //input filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'agent'             => [ 'required' => true,  ],
            'superieur1'        => [ 'required' => false,  ],
            'superieur2'        => [ 'required' => false,  ],
            'superieur3'        => [ 'required' => false,  ],
            'autorite1'         => [ 'required' => false,  ],
            'autorite2'         => [ 'required' => false,  ],
            'autorite3'         => [ 'required' => false,  ],
        ]));
    }
}
<?php

namespace Application\Form\AgentHierarchieCalcul;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Structure\Service\Structure\StructureServiceAwareTrait;

class AgentHierarchieCalculForm extends Form {
    use StructureServiceAwareTrait;

    public function init(): void
    {
        $this->add([
            'type' => Select::class,
            'name' => 'structure',
            'options' => [
                'label' => 'Structure  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => $this->getStructureService()->getStructuresAsOptions(),
            ],
            'attributes' => [
                'id' => 'structure',
                'class' => 'bootstrap-selectpicker',
                'data-live-search' => 'true'
            ],
        ]);
        //mode
        $this->add([
            'type' => Select::class,
            'name' => 'mode',
            'options' => [
                'label' => 'Mode  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    'preview' => "Prévisualisation",
                    'compute' => "Calcul",
                ]
            ],
            'attributes' => [
                'id' => 'mode',
            ],
        ]);
        //Submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => 'Calculer pour la structure',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'fichier' => [ 'required' => true,  ],
            'mode'  => ['required' => true, ],
        ]));
    }
}
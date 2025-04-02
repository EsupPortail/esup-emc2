<?php

namespace Application\Form\AgentHierarchieImportation;

use Agent\Service\AgentRef\AgentRefServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class AgentHierarchieImportationForm extends Form {

    use AgentRefServiceAwareTrait;

    public function init(): void
    {
        $sources = [];
        $sources['EMC2'] = "EMC2";
        $refSources = $this->getAgentRefService()->getSources();
        foreach ($refSources as $refSource) {
            $sources[$refSource] = $refSource;
        }

        //file CSV
        $this->add([
            'type' => File::class,
            'name' => 'fichier',
            'options' => [
                'label' => 'Chaînes hiérachique au format CSV <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
        ]);
        //source
        $this->add([
            'type' => Select::class,
            'name' => 'source',
            'options' => [
                'label' => 'Source utilisée pour identifier les agents <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => $sources,
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
                    'import' => "Importation",
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
                'label' => 'Traiter le ficher',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'fichier' => [ 'required' => true,  ],
            'source'  => ['required' => true, ],
            'mode'  => ['required' => true, ],
        ]));
    }
}
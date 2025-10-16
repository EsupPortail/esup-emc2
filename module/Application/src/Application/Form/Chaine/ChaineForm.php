<?php

namespace Application\Form\Chaine;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Date;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use UnicaenApp\Form\Element\SearchAndSelect;

class ChaineForm extends  Form {

    private string $urlAgent;

    public function setUrlAgent(string $urlAgent): void
    {
        $this->urlAgent = $urlAgent;
    }

    public function init(): void
    {
        //agent *
        $agent = new SearchAndSelect('agent', ['label' => "Agent·e :"]);
        $agent
            ->setAutocompleteSource($this->urlAgent)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'agent',
                'placeholder' => "Nom du l'agent·e ...",
            ]);
        $this->add($agent);
        $label = "Supérieur·e ou autorité<span class='icon icon-obligatoire'></span> :";
        $placeholder = "Nom du responsable ...";
        $responsable = new SearchAndSelect('responsable', ['label' => $label]);
        $responsable
            ->setAutocompleteSource($this->urlAgent)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'Responsable',
                'placeholder' => $placeholder,
            ]);
        $this->add($responsable);
        //datedebut *
        $this->add([
            'type' => Date::class,
            'name' => 'date_debut',
            'options' => [
                'label' => "Date de début<span class='icon icon-asterisque' title='Champ obligatoire' ></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'date_debut',
            ],
        ]);
        $this->add([
            'type' => Date::class,
            'name' => 'date_fin',
            'options' => [
                'label' => "Date de fin :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'date_fin',
            ],
        ]);
        $this->add([
            'type' => Checkbox::class,
            'name' => 'historisation',
            'options' => [
                'label' => "Historisation des chaînes existantes <span class='icon icon-info text-info' title='Les chaînes en cours seront historisées et ne seront plus considéré.'></span>",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'historisation',
            ],
        ]);
        $this->add([
            'type' => Checkbox::class,
            'name' => 'cloture',
            'options' => [
                'label' => "Clôture des chaînes existantes <span class='icon icon-info text-info' title='Les chaînes en cours recevront comme date de fin la date actuelle.'></span>",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'cloture',
            ],
        ]);
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
            'agent' => ['required' => true,],
            'responsable' => ['required' => true,],
            'date_debut' => ['required' => true,],
            'date_fin' => ['required' => false,],
            'historisation' => ['required' => false,],
            'cloture' => ['required' => false,],
        ]));
    }
}
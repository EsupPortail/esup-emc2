<?php

namespace Agent\Form\Chaine;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Date;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
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
                'id' => 'responsable',
                'placeholder' => $placeholder,
            ]);
        $this->add($responsable);

        $label = "Supérieur·e ou autorité<span class='icon icon-obligatoire'></span> :";
        $placeholder = "Nom du responsable ...";
        $responsableBis = new SearchAndSelect('responsable-bis', ['label' => $label]);
        $responsableBis
            ->setAutocompleteSource($this->urlAgent)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'responsable-bis',
                'placeholder' => $placeholder,
            ]);
        $this->add($responsableBis);

        $label = "Supérieur·e ou autorité<span class='icon icon-obligatoire'></span> :";
        $placeholder = "Nom du responsable ...";
        $responsableTer = new SearchAndSelect('responsable-ter', ['label' => $label]);
        $responsableTer
            ->setAutocompleteSource($this->urlAgent)
            ->setLabelOption('disable_html_escape',true)
            ->setAttributes([
                'id' => 'responsable-ter',
                'placeholder' => $placeholder,
            ]);
        $this->add($responsableTer);

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
                'label' => "Annulation des chaînes existantes <span class='icon icon-info text-info' title='Les chaînes en cours seront historisées et ne seront plus considéré.'></span>",
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
            'agent' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "Veuillez sélectionnez une personne dans la liste déroulante.",
                            ],
                            'callback' => function ($value, $context = []) {
                                $hasResponsable = (isset($context['agent']) AND isset($context['agent']['id']) AND trim($context['agent']['id']) !== '');
                                return $hasResponsable;
                            },
                        ],
                    ],
                ],
            ],
            'responsable' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "Veuillez sélectionnez une personne dans la liste déroulante.",
                            ],
                            'callback' => function ($value, $context = []) {
                                $hasResponsable = (isset($context['responsable']) AND isset($context['responsable']['id']) AND trim($context['responsable']['id']) !== '');
                                return $hasResponsable;
                            },
                        ],
                    ],
                ],
             ],
            'responsable-bis' => ['required' => false,],
            'responsable-ter' => ['required' => false,],
            'date_debut' => ['required' => true,],
            'date_fin' => ['required' => false,],
            'historisation' => ['required' => false,],
            'cloture' => ['required' => false,],
        ]));
    }
}
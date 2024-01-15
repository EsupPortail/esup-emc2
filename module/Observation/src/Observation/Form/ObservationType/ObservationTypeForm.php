<?php

namespace Observation\Form\ObservationType;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use Observation\Service\ObservationType\ObservationTypeServiceAwareTrait;

class ObservationTypeForm extends Form {
    use ObservationTypeServiceAwareTrait;

    private ?string $oldCode = null;

    public function setOldCode(?string $oldCode): void
    {
        $this->oldCode = $oldCode;
    }

    public function init(): void
    {
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code du type de validation <span class='icon obligatoire text-danger' title='Champ obligatoire et unique'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé du type de validation <span class='icon obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'categorie',
            'options' => [
                'label' => "Catégorie du type de validation :",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'categorie',
            ],
        ]);

        // button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'code'                => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce code existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if(!isset($context['old-code']) || $value == $context['old-code']) return true;
                            return ($this->getObservationTypeService()->getObservationTypeByCode($context['categorie']) === null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'libelle'             => [ 'required' => true,  ],
            'categorie'           => [ 'required' => false,  ],
        ]));
    }
}
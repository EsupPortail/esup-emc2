<?php

namespace EmploiRepere\Form\EmploiRepere;

use EmploiRepere\Service\EmploiRepere\EmploiRepereServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class EmploiRepereForm extends Form {

    use EmploiRepereServiceAwareTrait;

    private ?string $oldCode = null;

    public function setOldCode(?string $oldCode): void
    {
        $this->oldCode = $oldCode;
    }


    public function init(): void
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //description
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Description : ",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'tinymce',
            ],
        ]);

        //submit
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
        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle'               => [ 'required' => true,  ],
            'code'                  => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Le code doit être unique.",
                        ],
                        'callback' => function ($value, $context = []) {
                            $emploirepere = $this->getEmploiRepereService()->getEmploiRepereByCode($value);
                            if ($this->getObject()->getId() === null) {
                                return $emploirepere === null;
                            } else {
                                return $emploirepere->getId() === $this->getObject()->getId();
                            }
                        },
                    ],
                ]],
            ],
            'description'           => [ 'required' => false,  ],
        ]));
    }
}

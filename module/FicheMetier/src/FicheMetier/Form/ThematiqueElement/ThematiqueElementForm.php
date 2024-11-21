<?php

namespace FicheMetier\Form\ThematiqueElement;

use Element\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ThematiqueElementForm extends Form
{
    use NiveauServiceAwareTrait;

    public function init(): void
    {
        $this->add([
            'type' => Select::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau <span class='icon icon-obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'empty_option' => 'Sélectionner un niveau ...',
                'value_options' => $this->getNiveauService()->getMaitrisesNiveauxAsOptions('Thematique'),
            ],
            'attributes' => [
                'id' => 'niveau',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'complement',
            'options' => [
                'label' => "Complément :",
            ],
            'attributes' => [
                'id' => 'complement',
                'class' => 'tinymce',
            ],
        ]);
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'niveau' => ['required' => true,],
            'complement' => ['required' => false,],
        ]));
    }
}
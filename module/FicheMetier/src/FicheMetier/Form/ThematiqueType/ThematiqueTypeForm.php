<?php

namespace FicheMetier\Form\ThematiqueType;

use FicheMetier\Service\ThematiqueType\ThematiqueTypeServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class ThematiqueTypeForm extends Form
{
    use ThematiqueTypeServiceAwareTrait;

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
                'label' => "Code associé au type <span class='icon obligatoire text-danger' title='Champ obligatoire et unique'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé du type <span class='icon obligatoire text-danger' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Description du type de thématique ",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => 'tinymce',
            ],
        ]);
        $this->add([
            'type' => Radio::class,
            'name' => 'obligatoire',
            'options' => [
                'label' => "Type de thématique obligatoire <span class='icon obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => ['disable_html_escape' => true,],
                'value_options' => [
                    true => "Oui",
                    false => "Non",
                ],
            ],
            'attributes' => [
                'id' => 'obligatoire',
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
            'code'                  => [
                'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce code existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value == $this->oldCode) return true;
//                            return ($this->getEntityManager()->getRepository(EtatType::class)->findOneBy(['code'=>$value],[]) == null);;
                            return ($this->getThematiqueTypeService()->getThematiqueTypeByCode($value) == null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'libelle'               => [ 'required' => true,  ],
            'description'           => [ 'required' => false, ],
            'obligatoire'           => [ 'required' => true,  ],
        ]));
    }
}
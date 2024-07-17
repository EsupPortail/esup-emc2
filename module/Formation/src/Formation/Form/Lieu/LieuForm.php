<?php

namespace Formation\Form\Lieu;

use Formation\Service\Lieu\LieuServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;

class LieuForm extends Form {
    use LieuServiceAwareTrait;

    public function init(): void
    {
        //Libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);

        //batiment
        $this->add([
            'type' => Text::class,
            'name' => 'batiment',
            'options' => [
                'label' => "Bâtiment <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'batiment',
            ],
        ]);
        //campus
        $this->add([
            'type' => Text::class,
            'name' => 'campus',
            'options' => [
                'label' => "Campus <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'campus',
            ],
        ]);
        //ville
        $this->add([
            'type' => Text::class,
            'name' => 'ville',
            'options' => [
                'label' => "Ville <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'id' => 'ville',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer',
                'label_options' => ['disable_html_escape' => true,],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => [
                'required' => true,
                'validators' => [
                    [
                        'name' => Callback::class,
                        'options' => [
                            'messages' => [
                                Callback::INVALID_VALUE => "Un lieu existe déjà avec les mêmes informations",
                            ],
                            'callback' => function ($value, $context = []) {
                                $lieu = $this->getLieuService()->getLieuWithInfos($context['libelle'], $context['batiment'], $context['campus'], $context['ville']);
                                if ($lieu === null) return true;
                                if ($lieu->getId() === $this->getObject()->getId()) return true;
                                return false;
                            },
                            //'break_chain_on_failure' => true,
                        ],
                    ],
                ],
            ],
            'batiment' => ['required' => true],
            'campus' => ['required' => true],
            'ville' => ['required' => true],

        ]));
    }
}
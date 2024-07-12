<?php

namespace Formation\Form\Lieu;

use Formation\Service\Lieu\LieuServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

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
//                'validators' => [
//                    [
//                        'name' => Callback::class,
//                        'options' => [
//                            'messages' => [
//                                Callback::INVALID_VALUE => "Un·e formateur·trice utilise déjà cette adresse électronique",
//                            ],
//                            'callback' => function ($value, $context = []) {
//                                $lieux = $this->getLieuService()->getLieuWithArray($context);
//                                if (empty($lieux)) return true;
//                                if ($this->getOperation() === 'modification' and count($lieux) === 1 and current($lieux)->getEmail() === $value) return true;
//                                return false;
//                            },
//                            //'break_chain_on_failure' => true,
//                        ],
//                    ],
//                ],
            ],
            'batiment' => ['required' => true],
            'campus' => ['required' => true],
            'ville' => ['required' => true],

        ]));
    }
}
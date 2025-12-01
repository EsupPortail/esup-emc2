<?php

namespace Referentiel\Form\Referentiel;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Color;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Laminas\Validator\Callback;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;

class ReferentielForm extends Form
{
    use ReferentielServiceAwareTrait;

    private ?string $oldLibelleCourt = null;
    public function setOldLibelleCourt(?string $libelleCourt) { $this->oldLibelleCourt = $libelleCourt; }

    public function init(): void
    {
        //libelleCourt *1
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_court',
            'options' => [
                'label' => "Libellé court <span class='icon icon-info' title='Doit être unique, il servira à identifier le référentiel'></span><span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle_court',
            ],
        ]);
        // libelle long *
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_long',
            'options' => [
                'label' => "Libellé long <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle_long',
            ],
        ]);
        //couleur *
        $this->add([
            'type' => Color::class,
            'name' => 'couleur',
            'options' => [
                'label' => "Couleur :",
            ],
            'attributes' => [
                'id' => 'couleur',
            ],
        ]);
        //description
        $this->add([
            'type' => Textarea::class,
            'name' => 'description',
            'options' => [
                'label' => "Description :",
            ],
            'attributes' => [
                'id' => 'description',
                'class' => "tinymce",
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
            'libelle_court'           => [ 'required' => true,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce code existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value == $this->oldLibelleCourt) return true;
                            return ($this->getReferentielService()->getReferentielByLibelleCourt($value) == null);
                        },
                    ],
                ]],
            ],
            'libelle_long'            => [ 'required' => true, ],
            'couleur'                 => [ 'required' => true, ],
            'description'             => [ 'required' => false,],
        ]));

    }
}

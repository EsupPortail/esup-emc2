<?php

namespace Application\Form\FicheMetierEtat;

use Application\Service\FicheMetierEtat\FicheMetierEtatServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FicheMetierEtatForm extends Form {

    public function init()
    {
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code (utilisé pour la recherche) :",
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle (utilisé sur le badge) :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'description',
            'options' => [
                'label' => "Description (utilisée dans le formulaire) :",
            ],
            'attributes' => [
                'id' => 'description',
            ],
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'couleur',
            'options' => [
                'label' => "Couleur (utilisée sur le badge) :",
            ],
            'attributes' => [
                'id' => 'couleur',
            ],
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
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
            'code'          => [     'required' => true,     ],
            'libelle'       => [     'required' => true,     ],
            'description'   => [     'required' => true,     ],
            'couleur'       => [     'required' => true,     ],
        ]));
    }
}
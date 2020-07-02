<?php

namespace Application\Form\SelectionFicheMetierEtat;

use Application\Service\FicheMetierEtat\FicheMetierEtatServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class SelectionFicheMetierEtatForm extends Form {
    use FicheMetierEtatServiceAwareTrait;

    public function init()
    {
        $this->add([
            'type' => Select::class,
            'name' => 'etat',
            'options' => [
                'label' => "État de la fiche métier :",
                'empty_option' => "Sélectionner un état ...",
                'value_options' => $this->getFicheMetierEtatService()->getEtatsAsOption(),
            ],
            'attributes' => [
                'id' => 'etat',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
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
            'etat' => [
                'required' => true,
            ],
        ]));
    }
}
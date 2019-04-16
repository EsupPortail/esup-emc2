<?php

namespace Application\Form\Fonction;

use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class FonctionForm extends Form {

    public function init()
    {
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_masculin',
            'options' => [
                'label' => "Libellé masculin :",
            ],
            'attributes' => [
                'id' => 'libelle_masculin',
            ],
        ]);
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_feminin',
            'options' => [
                'label' => "Libellé féminin :",
            ],
            'attributes' => [
                'id' => 'libelle_feminin',
            ],
        ]);
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer la fonction',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

    }
}
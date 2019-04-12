<?php

namespace Application\Form\FichePosteCreation;

use Zend\Form\Element\Button;
use Zend\Form\Element\Text;
use Zend\Form\Form;

class FichePosteCreationForm extends Form {

    public function init()
    {
        //Champs texte obligatoire LIBELLE
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);

//        submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Créer la fiche de poste',
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
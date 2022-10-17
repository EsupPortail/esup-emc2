<?php 

namespace Application\Form\AssocierTitre;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class AssocierTitreForm extends Form {

    public function init()
    {
        // libelle
        $this->add([
            'type' => Text::class,
            'name' => 'titre',
            'options' => [
                'label' => "Complément de titre :",
            ],
            'attributes' => [
                'id' => 'titre',
            ],
        ]);
        // button
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
            'titre' => [
                'required' => false,
            ],
        ]));
    }
}
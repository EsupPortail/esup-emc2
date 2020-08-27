<?php

namespace Application\Form\FonctionActivite;

use Application\Service\Fonction\FonctionServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FonctionActiviteForm extends Form {
    use FonctionServiceAwareTrait;

    public function init()
    {
        /** CODE */
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code* :",
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        /** LIBELLE */
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libelle* :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        /** Destination */
        $this->add([
            'type' => Select::class,
            'name' => 'destination',
            'options' => [
                'label' => "Destination associée* :",
                'value_options' => $this->getFonctionService()->getDestinationsAsOptions(),
            ],
            'attributes' => [
                'id'                => 'destination',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

        /** SUBMIT */
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

        /** INPUTFILTER */
        $this->setInputFilter((new Factory())->createInputFilter([
            'code'              => [ 'required' => true,  ],
            'libelle'           => [ 'required' => true,  ],
            'destination'       => [ 'required' => true,  ],
        ]));
    }
}
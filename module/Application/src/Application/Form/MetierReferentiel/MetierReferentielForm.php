<?php

namespace Application\Form\MetierReferentiel;

use Application\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class MetierReferentielForm extends Form {
    use DomaineServiceAwareTrait;

    public function init()
    {
        // libelle court
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_court',
            'options' => [
                'label' => "Libellé court:",
            ],
            'attributes' => [
                'id' => 'libelle_court',
            ],
        ]);
        // libelle long
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_long',
            'options' => [
                'label' => "Libellé long :",
            ],
            'attributes' => [
                'id' => 'libelle_long',
            ],
        ]);
        // prefix
        $this->add([
            'type' => Text::class,
            'name' => 'prefix',
            'options' => [
                'label' => "Préfixe des adresse su référentiel :",
            ],
            'attributes' => [
                'id' => 'prefix',
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

        //inputFIlter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle_court'          => [ 'required' => true,  ],
            'libelle_long'           => [ 'required' => true,  ],
            'prefix'                 => [ 'required' => false, ],
        ]));
    }
}
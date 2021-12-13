<?php

namespace Metier\Form\Referentiel;

use Metier\Entity\Db\Referentiel;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Radio;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ReferentielForm extends Form {
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
                'label' => "Préfixe du référentiel :",
            ],
            'attributes' => [
                'id' => 'prefix',
            ],
        ]);
        // type
        $this->add([
            'type' => Radio::class,
            'name' => 'type',
            'options' => [
                'label' => "Type du référentiel * :",
                'value_options' => [
                    Referentiel::PDF => "PDF",
                    Referentiel::WEB => "Web",
                    Referentiel::VIDE => "Vide",
                ],
            ],
            'attributes' => [
                'id' => 'type',
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
            'type'                   => [ 'required' => true, ],
        ]));
    }
}
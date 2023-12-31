<?php

namespace Metier\Form\Referentiel;

use Metier\Entity\Db\Referentiel;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Radio;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ReferentielForm extends Form {
    use DomaineServiceAwareTrait;

    public function init(): void
    {
        // libelle court
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_court',
            'options' => [
                'label' => "Libellé court <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
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
                'label' => "Libellé long <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
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
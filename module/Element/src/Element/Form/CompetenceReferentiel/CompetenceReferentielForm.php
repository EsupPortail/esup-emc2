<?php

namespace Element\Form\CompetenceReferentiel;

use Element\Service\Competence\CompetenceServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Color;
use Laminas\Form\Element\Text;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class CompetenceReferentielForm extends Form {
    use CompetenceServiceAwareTrait;

    public function init(): void
    {

        //libelle court
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_court',
            'options' => [
                'label' => "Libelle court <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle_court',
            ],
        ]);
        //libelle long
        $this->add([
            'type' => Text::class,
            'name' => 'libelle_long',
            'options' => [
                'label' => "Libelle long <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle_long',
            ],
        ]);
        //couleur
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
        //submit
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

        //filter
        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle_court'           => [ 'required' => true,  ],
            'libelle_long'            => [ 'required' => true,  ],
            'couleur'                 => [ 'required' => false,  ],
        ]));
    }
}
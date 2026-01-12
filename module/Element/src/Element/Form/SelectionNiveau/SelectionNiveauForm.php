<?php

namespace Element\Form\SelectionNiveau;

use Element\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionNiveauForm extends Form {
    use NiveauServiceAwareTrait;

    private ?string $type = null;

    /**
     * @param string $type
     * @return SelectionNiveauForm
     */
    public function setType(string $type = ""): SelectionNiveauForm
    {
        $this->type = $type;
        return $this;
    }

    public function init(): void
    {
        //select :: niveau
        $this->add([
            'type' => Select::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau de maîtrise <span class='icon icon-obligatoire' title='Champ obligatoire'></span> :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner un niveau de maîtrise",
                'value_options' => $this->getNiveauService()->getMaitrisesNiveauxAsOptions(($this->type)??""),
            ],
            'attributes' => [
                'id'                => 'niveau',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        $this->add([
            'type' => Checkbox::class,
            'name' => 'clef',
            'options' => [
                'label' => "Est clef",
            ],
            'attributes' => [
                'id'                => 'clef',
            ],
        ]);
        //button
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
                'class' => 'btn btn-success',
            ],
        ]);
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'niveau'           => [ 'required' => true,  ],
            'clef'             => [ 'required' => false,  ],
        ]));
    }

    public function masquerClef(): void
    {
        $this->remove('clef');
    }
}
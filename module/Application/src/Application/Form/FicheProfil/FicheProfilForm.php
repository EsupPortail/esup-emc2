<?php

namespace Application\Form\FicheProfil;

use Application\Entity\Db\Structure;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Date;
use Zend\Form\Element\DateTime;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class FicheProfilForm extends Form {
    use FichePosteServiceAwareTrait;
    use StructureServiceAwareTrait;
    use DateTimeAwareTrait;

    /** @var Structure */
    private $structure;

    public function setStructure(Structure $structure)
    {
        $this->structure = $structure;
    }

    public function init()
    {
        //structure (non editable)
        $this->add([
            'type' => Text::class,
            'name' => 'structure',
            'options' => [
                'label' => "Structure de rattachement * :",
            ],
            'attributes' => [
                'id' => 'structure',
                'readonly' => true,
                'value' => ($this->getObject())?$this->getObject()->getStructure()->getLibelleLong():null,
            ],
        ]);
        $this->add([
            'type' => Hidden::class,
            'name' => 'structure_id',
            'attributes' => [
                'id' => 'structure_id',
                'readonly' => true,
                'value' => ($this->getObject())?$this->getObject()->getStructure()->getId():null,
            ],
        ]);
        //fichedeposte
        $this->add([
            'name' => 'ficheposte',
            'type' => Select::class,
            'options' => [
                'label' => 'Fiche de poste de référence * : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
                'empty_option' => "Sélectionner une fiche de poste ... ",
                'value_options' => ($this->structure)?$this->getFichePosteService()->getFichesPostesByStructuresAsOptions([$this->structure], true):null,
            ],
            'attributes' => [
                'id'                => 'competence',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ]
        ]);
        //date
        $this->add([
            'type' => DateTime::class,
            'name' => 'date',
            'options' => [
                'label' => "Date de fin * :",
                'format' => 'd/m/Y',
            ],
            'attributes' => [
                'id' => 'date',
//                'min' => $this->getDateTime(),
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'lieu',
            'options' => [
                'label' => "Lieu :",
            ],
            'attributes' => [
                'id' => 'lieu',
                'class' => 'tinymce',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'contexte',
            'options' => [
                'label' => "Contexte :",
            ],
            'attributes' => [
                'id' => 'contexte',
                'class' => 'tinymce',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'mission',
            'options' => [
                'label' => "Mission :",
            ],
            'attributes' => [
                'id' => 'mission',
                'class' => 'tinymce',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau requis :",
            ],
            'attributes' => [
                'id' => 'niveau',
                'class' => 'tinymce',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'contraintes',
            'options' => [
                'label' => "Contraintes liées au poste :",
            ],
            'attributes' => [
                'id' => 'contraintes',
                'class' => 'tinymce',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'contrat',
            'options' => [
                'label' => "Contrat :",
            ],
            'attributes' => [
                'id' => 'contrat',
                'class' => 'tinymce',
            ],
        ]);
        $this->add([
            'type' => Textarea::class,
            'name' => 'renumeration',
            'options' => [
                'label' => "Rénumération :",
            ],
            'attributes' => [
                'id' => 'renumeration',
                'class' => 'tinymce',
            ],
        ]);
        //bouton
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

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'structure'               => [ 'required' => true,  ],
            'ficheposte'              => [ 'required' => true,  ],
            'date'                    => [ 'required' => true,  ],
            'lieu'                    => [ 'required' => false,  ],
            'contexte'                => [ 'required' => false,  ],
            'mission'                 => [ 'required' => false,  ],
            'niveau'                  => [ 'required' => false,  ],
            'contraintes'             => [ 'required' => false,  ],
            'contrat'                 => [ 'required' => false,  ],
            'remuneration'            => [ 'required' => false,  ],
        ]));
    }
}
<?php

namespace Application\Form\FicheProfil;

use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use DateTime;
use Structure\Entity\Db\Structure;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Checkbox;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class FicheProfilForm extends Form {
    use FichePosteServiceAwareTrait;
    use StructureServiceAwareTrait;

    /** @var Structure */
    private $structure;

    public function setStructure(Structure $structure)
    {
        $this->structure = $structure;
    }

    public function init()
    {
        $structures = null;
        if ($this->structure) {
            $structures = $this->getStructureService()->getStructuresFilles($this->structure);
            $structures[] = $this->structure;
        }

        $this->add([
            'type' => Checkbox::class,
            'name' => 'vacance_emploi',
            'options' => [
                'label' => "Il s'agit d'une vacance d'emploi",
            ],
            'attributes' => [
                'id' => 'vacance_emploi',
            ],
        ]);
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
                'value_options' => ($this->structure AND $structures !== null)?$this->getFichePosteService()->getFichesPostesRecrutementByStructuresAsOptions($structures, true):null,
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
            'name' => 'date_dossier',
            'options' => [
                'label' => "Date de fin de dépôt des dossiers * :",
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id' => 'date_dossier',
//                'min' => (new DateTime()),
            ],
        ]);
        $this->add([
            'type' => DateTime::class,
            'name' => 'date_audition',
            'options' => [
                'label' => "Date prévisionnelle d'audition :",
                'format' => 'd/m/Y'
            ],
            'attributes' => [
                'id' => 'date_audition',
//                'min' => (new DateTime()),
            ],
        ]);
        //email
        $this->add([
            'type' => Text::class,
            'name' => 'adresse',
            'options' => [
                'label' => "Adresse électronique de contact * :",
            ],
            'attributes' => [
                'id' => 'adresse',
                'value' => $adresse,
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
                'label' => "Niveau requis * :",
            ],
            'attributes' => [
                'id' => 'niveau',
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
                'class' => 'btn btn-primary action',
            ],
        ]);

        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'vacance_emploi'          => [ 'required' => true,  ],
            'structure'               => [ 'required' => true,  ],
            'ficheposte'              => [ 'required' => true,  ],
            'date_dossier'            => [ 'required' => true,  ],
            'date_audition'           => [ 'required' => false,  ],
            'adresse'                 => [ 'required' => true,  ],
            'lieu'                    => [ 'required' => false,  ],
            'contexte'                => [ 'required' => false,  ],
            'mission'                 => [ 'required' => false,  ],
            'niveau'                  => [ 'required' => true,  ],
            'contrat'                 => [ 'required' => false,  ],
            'remuneration'            => [ 'required' => false,  ],
        ]));
    }
}
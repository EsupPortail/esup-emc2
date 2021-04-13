<?php 

namespace Autoform\Form\Champ;

use Autoform\Entity\Db\Champ;
use Zend\Form\Element\Button;
use Zend\Form\Element\Select;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class ChampForm extends Form {

    public function init()
    {
        // type
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de mise en forme* :",
                'value_options' => [
                    Champ::TYPE_TEXT            => "Texte libre court",
                    Champ::TYPE_MULTIPLE_TEXT   => "Multiple texte libre court",
                    Champ::TYPE_CUSTOM          => "Multiple champs parametrable",
                    Champ::TYPE_TEXTAREA        => "Texte libre long ",
                    Champ::TYPE_CHECKBOX        => "Boîte à cocher",
                    Champ::TYPE_NOMBRE          => "Nombre",
                    Champ::TYPE_SELECT          => "Choix simple parmi selection",
                    Champ::TYPE_MULTIPLE        => "Choix multiple parmi selection",
                    Champ::TYPE_PERIODE         => "Période",
                    Champ::TYPE_FORMATION       => "Formation",
                    Champ::TYPE_ANNEE           => "Année Scolaire",
                    Champ::TYPE_ENTITY          => "Choix parmi instance d'une entité",
                    Champ::TYPE_ENTITY_MULTI    => "Choix multiples parmi instance d'une entité",
                    Champ::TYPE_LABEL           => "Texte non éditable",
                    Champ::TYPE_SPACER          => "Espace de séparation",

                ],
            ],
            'attributes' => [
                'id' => 'type',
            ],
        ]);
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
        // options
        $this->add([
            'type' => Text::class,
            'name' => 'options',
            'options' => [
                'label' => "Options (séparer les options avec des ';') :",
            ],
            'attributes' => [
                'id' => 'texte',
            ],
        ]);
        // texte
        $this->add([
            'type' => Text::class,
            'name' => 'texte',
            'options' => [
                'label' => "Texte :",
            ],
            'attributes' => [
                'id' => 'texte',
            ],
        ]);
        //bouton
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer la champ',
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
            'type' => [
                'required' => true,
            ],
            'libelle' => [
                'required' => true,
            ],
            'texte' => [
                'required' => false,
            ],
        ]));
    }
}




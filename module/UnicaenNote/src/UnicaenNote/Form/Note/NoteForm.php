<?php

namespace UnicaenNote\Form\Note;

use UnicaenNote\Service\PorteNote\PorteNoteServiceAwareTrait;
use UnicaenNote\Service\Type\TypeServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Element\Textarea;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class NoteForm extends Form {
    use PorteNoteServiceAwareTrait;
    use TypeServiceAwareTrait;

    public function init()
    {
        //PORTE NOTE
//        $this->add([
//            'type' => Hidden::class,
//            'name' => 'porte-note',
//            'options' => [
//                'label' => "Porte-notes *  :",
//                'empty_option' => "Aucun porte-notes",
//                'value_options' => $this->getPorteNoteService()->getPortesNotesAsOptions(),
//            ],
//            'attributes' => [
//                'id' => 'porte-note',
//            ],
//        ]);
        //TYPE
        $this->add([
            'type' => Select::class,
            'name' => 'type',
            'options' => [
                'label' => "Type de la note  :",
                'empty_option' => "Aucun type",
                'value_options' => $this->getTypeService()->getTypesAsOptions(),
            ],
            'attributes' => [
                'id' => 'type',
            ],
        ]);
        //Contenu
        $this->add([
            'name' => 'description',
            'type' => Textarea::class,
            'options' => [
                'label' => 'Contenu : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
            ]
        ]);
        //bouton
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer' ,
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
//            'porte-note'    => [ 'required' => false, ],
            'type'          => [ 'required' => false, ],
            'description'   => [ 'required' => false, ],
        ]));
    }
}
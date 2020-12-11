<?php

namespace UnicaenNote\Form\PorteNote;

use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenNote\Entity\Db\PorteNote;
use UnicaenPrivilege\Entity\Db\Privilege;
use Zend\Form\Element\Button;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Text;
use Zend\Form\Form;
use Zend\InputFilter\Factory;
use Zend\Validator\Callback;

class PorteNoteForm extends Form {
    use EntityManagerAwareTrait;

    public function init()
    {
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Accroche :",
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        $this->add([
            'name' => 'old-libelle',
            'type' => Hidden::class,
            'attributes' => [
                'value' => "",
            ],
        ]);
        //submit
        $this->add([
            'type' => Button::class,
            'name' => 'bouton',
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

        $this->setInputFilter((new Factory())->createInputFilter([
            'libelle' => [
                'required' => false,
                'validators' => [[
                    'name' => Callback::class,
                    'options' => [
                        'messages' => [
                            Callback::INVALID_VALUE => "Ce libellé existe déjà",
                        ],
                        'callback' => function ($value, $context = []) {
                            if($value === null OR trim($value) == '') return true;
                            if($value == $context['old-libelle']) return true;
                            return ($this->getEntityManager()->getRepository(PorteNote::class)->findOneBy(['accroche'=>$value],[]) == null);
                        },
                        //'break_chain_on_failure' => true,
                    ],
                ]],
            ],
            'old-libelle' => ['required' => false, ],
        ]));
    }

    public function setOldLibelle($value){
        $this->get('old-libelle')->setValue($value);
    }
}
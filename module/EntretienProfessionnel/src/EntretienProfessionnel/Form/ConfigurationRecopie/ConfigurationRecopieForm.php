<?php

namespace EntretienProfessionnel\Form\ConfigurationRecopie;

use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use UnicaenAutoform\Service\Formulaire\FormulaireServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class ConfigurationRecopieForm extends Form {
    use FormulaireServiceAwareTrait;

    public function init()
    {
        //id => code
        $formulaire = $this->getFormulaireService()->getFormulaireByCode(EntretienProfessionnel::FORMULAIRE_CREP);
        $options = $this->getFormulaireService()->getChampsAsOptions($formulaire);

        //operation
        $this->add([
            'type' => Hidden::class,
            'name' => 'operation',
            'attributes' => [
                'value' => "recopie",
            ],
        ]);
        //From
        $this->add([
            'type' => Select::class,
            'name' => 'from',
            'options' => [
                'label' => "Champ à recopier de l'entretien professionnel précédent * :",
                'empty_option' => "Choisissez un champ à copier ...",
                'value_options' => $options,
            ],
            'attributes' => [
                'id' => 'from',
                'class' => 'selectpicker form-control',
                'data-live-search' => true,
            ],
        ]);
        //To
        $this->add([
            'type' => Select::class,
            'name' => 'to',
            'options' => [
                'label' => "Champ qui recevra le champ copié * :",
                'empty_option' => "Choisissez un champ à remplir ...",
                'value_options' => $options,
            ],
            'attributes' => [
                'id' => 'to',
                'class' => 'selectpicker form-control',
                'data-live-search' => true,
            ],
        ]);
        //submit
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
        //input
        $this->setInputFilter((new Factory())->createInputFilter([
            'operation'      => [ 'required' => true,  ],
            'from'           => [ 'required' => true,  ],
            'to'             => [ 'required' => true,  ],
        ]));
    }
}

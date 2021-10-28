<?php

namespace Application\Form\SelectionMaitriseNiveau;

use Application\Service\MaitriseNiveau\MaitriseNiveauServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class SelectionMaitriseNiveauForm extends Form {
    use MaitriseNiveauServiceAwareTrait;

    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     * @return SelectionMaitriseNiveauForm
     */
    public function setType(string $type = ""): SelectionMaitriseNiveauForm
    {
        $this->type = $type;
        return $this;
    }

    public function init()
    {
        //select :: niveau
        $this->add([
            'type' => Select::class,
            'name' => 'niveau',
            'options' => [
                'label' => "Niveau de maîtrise * :",
                'empty_option' => "Sélectionner un niveau de maîtrise ...",
                'value_options' => $this->getMaitriseNiveauService()->getMaitrisesNiveauxAsOptions(($this->type)??""),
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
                'class' => 'btn btn-primary',
            ],
        ]);
        //inputfilter
        $this->setInputFilter((new Factory())->createInputFilter([
            'niveau'           => [ 'required' => true,  ],
            'clef'             => [ 'required' => false,  ],
        ]));
    }

    public function masquerClef()
    {
        $this->remove('clef');
    }
}
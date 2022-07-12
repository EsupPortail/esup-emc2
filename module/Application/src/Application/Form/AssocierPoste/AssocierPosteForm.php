<?php

namespace Application\Form\AssocierPoste;

use Application\Service\Poste\PosteServiceAwareTrait;
use Structure\Entity\Db\Structure;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class AssocierPosteForm extends Form {
    use EntityManagerAwareTrait;
    use PosteServiceAwareTrait;

    public function init()
    {
        //Selection obligatoire AFFECTATION
        $this->add([
            'type' => Select::class,
            'name' => 'poste',
            'options' => [
                'label' => "Poste :",
                'empty_option' => "Sélectionner un poste ...",
                'value_options' => $this->generateSelectOptions(),
            ],
            'attributes' => [
                'id' => 'poste',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);

//        submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Associer le poste',
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
            'poste' => [
                'name' => 'poste',
                'required' => false,
            ],
        ]));
    }

    private function generateSelectOptions()
    {
        $postes = $this->getPosteService()->getPostes();
        $options = [];
        foreach ($postes as $poste) {
            if ($poste->getFichePoste() === NULL) {
                $options[$poste->getId()] = $poste->getNumeroPoste() . " - " . $poste->getDomaine()->getLibelle() . " - " . $poste->getStructure()->getLibelleCourt();
            }
        }
        return $options;
    }

    /**
     * @param Structure $structure
     * @param boolean $sousstructure
     * @return AssocierPosteForm
     */
    public function reinitWithStructure($structure, $sousstructure = false)
    {
        //agent
        $postes = $this->getPosteService()->getPostesByStructure($structure, $sousstructure, true);
        $posteOptions = [];
        foreach ($postes as $poste) {
            $posteOptions[$poste->getId()] = $poste->getNumeroPoste() . " - ". $poste->getDomaine()->getLibelle() . " - ".$poste->getStructure()->getLibelleCourt();
        }
        /** @var Select $this->get('agent') */
        $this->get('poste')->setValueOptions($posteOptions);

        return $this;
    }
}
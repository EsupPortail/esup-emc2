<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Hidden;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AjouterFicheMetierForm extends Form {
    use FicheMetierServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;

    private $previous;

    /**
     * @return mixed
     */
    public function getPrevious()
    {
        return $this->previous;
    }

    /**
     * @param mixed $previous
     * @return AjouterFicheMetierForm
     */
    public function setPrevious($previous)
    {
        $this->previous = $previous;
        return $this;
    }

    public function init()
    {
        //Fiche Type
        $this->add([
            'type' => Select::class,
            'name' => 'fiche_type',
            'options' => [
                'label' => "Fiche type :",
                'empty_option' => 'Sélectionner une fiche type ...',
                'value_options' => $this->generateFicheTypeOptions(),
            ],
            'attributes' => [
                'id' => 'fiche_type',
            ],
        ]);

        $this->add([
            'type' => Hidden::class,
            'name' => 'old',
            'attributes' => [
                'value' => $this->previous,
            ],
        ]);

        //Quotite
        //Fiche Type
        $this->add([
            'type' => Select::class,
            'name' => 'quotite',
            'options' => [
                'label' => "Quotité* :",
                'empty_option' => 'Préciser la quotité associée ...',
                'value_options' => $this->generateQuotiteOptions(),
            ],
            'attributes' => [
                'id' => 'quotite',
            ],
        ]);

        //Principale
        $this->add([
            'type' => Checkbox::class,
            'name' => 'est_principale',
            'options' => [
                'label' => "est la fiche principale",
            ],
            'attributes' => [
                'id' => 'est_principale',
            ],
        ]);

//        submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer l\'association',
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
            'fiche_type'        => [ 'required' => true,  ],
            'quotite'           => [ 'required' => true,  ],
            'old'               => [ 'required' => false,  ],
        ]));
    }

    private function generateFicheTypeOptions()
    {
        $options = [];
        $familles = $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles();
        foreach ($familles as $famille) {
            $fiches = $this->getFicheMetierService()->getFicheByFamille($famille);
            $optionsoptions = [];
            foreach ($fiches as $fiche) {
                $optionsoptions[$fiche->getId()] = $fiche->getMetier()->getLibelle();
            }
            $array = [
                'label' => $famille->getLibelle(),
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }

//
//
//        foreach ($fiches as $fiche) {
//            $options[$fiche->getId()] = $fiche->getMetier()->getLibelle();
//        }
        return $options;
    }

    private function generateQuotiteOptions()
    {
        $options = [];
        for($i = 20; $i <= 100; $i+=10) {
            $options[$i] = $i. '%';
        }
        return $options;

    }
}
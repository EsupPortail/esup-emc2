<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Application\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\Checkbox;
use Zend\Form\Element\Select;
use Zend\Form\Form;
use Zend\InputFilter\Factory;

class AjouterFicheMetierForm extends Form {
    use FicheMetierServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;

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
        ]));
    }


//array(
//    'european' => array(
//        'label' => 'European languages',
//        'options' => array(
//            '0' => 'French',
//            '1' => 'Italian',
//        ),
//    ),
//    'asian' => array(
//        'label' => 'Asian languages',
//        'options' => array(
//            '2' => 'Japanese',
//            '3' => 'Chinese',
//        ),
//    ),
//)

/**
<?php foreach ($familles as $famille) : ?>
            <optgroup label="<?php echo $famille->getLibelle(); ?>">
                <?php $metiers = $famille->getMetiers() ; ?>
                <?php foreach ($metiers as $metierOpt) : ?>
                    <option value="<?php $metierOpt->getId()?>" <?php if($metier) echo ($metier->getId() === $metierOpt->getId())?"selected":""; ?> >
                        <?php echo $metierOpt->getLibelle(); ?>
                    </option>
                <?php endforeach; ?>
            </optgroup>
        <?php endforeach; ?>
   */
    private function generateFicheTypeOptions()
    {
//        $fiches = $this->getFicheMetierService()->getFichesMetiers();

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
<?php

namespace FicheReferentiel\Form\Importation;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Hidden;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;

class ImportationForm extends Form {

    use ReferentielServiceAwareTrait;

    public function init(): void
    {
        //file CSV
        $this->add([
            'type' => File::class,
            'name' => 'fichier',
            'options' => [
                'label' => 'Fichier à importer <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'accept' => '.csv',
            ],
        ]);
        //save du fichier
        $this->add([ 'type' => Hidden::class, 'name' => 'file_name', ]);
        $this->add([ 'type' => Hidden::class, 'name' => 'file_tmp_name', ]);
        //referentiel
        $this->add([
            'type' => Select::class,
            'name' => 'referentiel',
            'options' => [
                'label' => 'Référentiel  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'empty_option' => "Sélectionner le référentiel associé",
                'value_options' => $this->getReferentielService()->getReferentielsAsOptions(),
            ],
            'attributes' => [
                'id' => 'referentiel',
            ],
        ]);
        //mode
        $this->add([
            'type' => Select::class,
            'name' => 'mode',
            'options' => [
                'label' => 'Mode  <span class="icon icon-asterisque" title="Champ obligatoire"></span> :',
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => [
                    'preview' => "Prévisualisation",
                    'import' => "Importation",
                ]
            ],
            'attributes' => [
                'id' => 'mode',
            ],
        ]);
        //Submit
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => 'Traiter le ficher',
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);

        $this->setInputFilter((new Factory())->createInputFilter([
            'fichier'       => [ 'required' => true, ],
            'file_name'     => [ 'required' => false, ],
            'file_tmp_name' => [ 'required' => false, ],
            'referentiel' => [ 'required' => true, ],
            'mode'  => ['required' => true, ],
        ]));
    }
}
<?php

namespace Fichier\Form\Upload;

use Fichier\Service\Nature\NatureServiceAwareTrait;
use Zend\Form\Element\Button;
use Zend\Form\Element\File;
use Zend\Form\Element\Select;
use Zend\Form\Form;

class UploadForm extends Form {
    use NatureServiceAwareTrait;

    public function init()
    {
        //upload
        $this->add([
            'type' => File::class,
            'name' => 'fichier',
            'options' => [
                'label' => 'Fichier à téléverser :',
            ],
        ]);
        //nature
        $this->add([
            'type' => Select::class,
            'name' => 'nature',
            'options' => [
                'label' => "Nature du fichier* :",
                'value_options' => $this->getNatureService()->getNaturesAsOptions(),
            ],
            'attributes' => [
                'id'                => 'nature',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'creer',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Téléverser le fichier',
                'label_options' => [
                    'disable_html_escape' => true,
                ],
            ],
            'attributes' => [
                'type' => 'submit',
                'class' => 'btn btn-primary',
            ],
        ]);
    }
}
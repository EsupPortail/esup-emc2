<?php

namespace Fichier\Form\Upload;

use Fichier\Service\Nature\NatureServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\File;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;

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
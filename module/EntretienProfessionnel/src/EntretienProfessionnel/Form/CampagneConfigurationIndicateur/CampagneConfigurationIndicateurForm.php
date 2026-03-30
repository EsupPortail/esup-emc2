<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationIndicateur;

use Laminas\Form\Element\Button;
use Laminas\Form\Element\Text;
use Laminas\Form\Element\Textarea;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class CampagneConfigurationIndicateurForm extends Form
{

    public function init(): void
    {
        // code
        $this->add([
            'type' => Text::class,
            'name' => 'code',
            'options' => [
                'label' => "Code <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'code',
            ],
        ]);
        //libelle
        $this->add([
            'type' => Text::class,
            'name' => 'libelle',
            'options' => [
                'label' => "Libellé <span class='icon icon-obligatoire' title='Champ obligatoire'></span>:",
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'id' => 'libelle',
            ],
        ]);
        //requete
        $this->add([
            'name' => 'requete',
            'type' => Textarea::class,
            'options' => [
                'label' => "Requête <span class='icon icon-obligatoire' title='Champ obligatoire'></span> <span class='icon icon-info' title='Si vous avez besoin de référencer la campagne, vous pouvez utiliser :campagne'></span> : ",
                'label_options' => [ 'disable_html_escape' => true, ],
                'label_attributes' => [ 'class' => 'control-label', ],
            ],
            'attributes' => [
                'id' => 'requete',
                'class' => 'tinymce type2',
            ],
        ]);
        //button
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
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
            'code'               => [ 'required' => true, ],
            'libelle'            => [ 'required' => true, ],
            'requete'            => [ 'required' => true, ],
        ]));
    }
}

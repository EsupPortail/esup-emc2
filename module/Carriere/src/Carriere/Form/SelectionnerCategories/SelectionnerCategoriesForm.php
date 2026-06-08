<?php

namespace Carriere\Form\SelectionnerCategories;

use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Select;
use Laminas\Form\Form;
use Laminas\InputFilter\Factory;

class SelectionnerCategoriesForm extends Form
{
    use CategorieServiceAwareTrait;

    public function init(): void
    {
        // select categorie
        $this->add([
            'type' => Select::class,
            'name' => 'categories',
            'options' => [
                'label' => "Catégories statutaires :",
                'label_options' => [ 'disable_html_escape' => true, ],
                'value_options' => $this->getCategorieService()->getCategorieAsOption(),
            ],
            'attributes' => [
                'id' => 'categorie',
                'class'             => 'bootstrap-selectpicker show-tick',
                'data-live-search'  => 'true',
                'multiple'  => 'multiple',
            ],
        ]);
        // button
        $this->add([
            'type' => Button::class,
            'name' => 'submit',
            'options' => [
                'label' => '<i class="fas fa-save"></i> Enregistrer ',
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
            'categories' => [ 'required' => false,  ],
        ]));
    }
}

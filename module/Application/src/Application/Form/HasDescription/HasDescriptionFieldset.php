<?php

namespace Application\Form\HasDescription;

use Zend\Form\Fieldset;

class HasDescriptionFieldset extends Fieldset {

    public function init()
    {
        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [
                    'class' => 'control-label',
                ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
                'id' => 'description',
            ]
        ]);
    }
}
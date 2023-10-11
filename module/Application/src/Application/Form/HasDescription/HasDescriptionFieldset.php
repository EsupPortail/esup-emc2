<?php

namespace Application\Form\HasDescription;

use Laminas\Form\Fieldset;

class HasDescriptionFieldset extends Fieldset {

    public function init(): void
    {
        // description
        $this->add([
            'name' => 'description',
            'type' => 'textarea',
            'options' => [
                'label' => 'Description : ',
                'label_attributes' => [ 'class' => 'control-label', ],
                'label_options' => [ 'disable_html_escape' => true, ],
            ],
            'attributes' => [
                'class' => 'type2 form-control',
                'id' => 'description',
            ]
        ]);
    }
}
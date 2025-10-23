<?php

namespace Carriere\Form\FonctionType;

trait FonctionTypeFormAwareTrait
{
    private FonctionTypeForm $fonctionTypeForm;

    public function getFonctionTypeForm(): FonctionTypeForm
    {
        return $this->fonctionTypeForm;
    }

    public function setFonctionTypeForm(FonctionTypeForm $fonctionTypeForm): void
    {
        $this->fonctionTypeForm = $fonctionTypeForm;
    }


}

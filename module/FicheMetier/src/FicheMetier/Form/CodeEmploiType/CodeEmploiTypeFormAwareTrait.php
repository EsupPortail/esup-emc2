<?php

namespace FicheMetier\Form\CodeEmploiType;

trait CodeEmploiTypeFormAwareTrait {

    private CodeEmploiTypeForm $codeEmploiTypeForm;

    public function getCodeEmploiTypeForm(): CodeEmploiTypeForm
    {
        return $this->codeEmploiTypeForm;
    }

    public function setCodeEmploiTypeForm(CodeEmploiTypeForm $codeEmploiTypeForm): void
    {
        $this->codeEmploiTypeForm = $codeEmploiTypeForm;
    }

}
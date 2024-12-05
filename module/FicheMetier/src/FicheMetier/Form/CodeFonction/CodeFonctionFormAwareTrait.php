<?php

namespace FicheMetier\Form\CodeFonction;

trait CodeFonctionFormAwareTrait {

    protected CodeFonctionForm $codeFonctionForm;

    public function getCodeFonctionForm(): CodeFonctionForm
    {
        return $this->codeFonctionForm;
    }

    public function setCodeFonctionForm(CodeFonctionForm $codeFonctionForm): void
    {
        $this->codeFonctionForm = $codeFonctionForm;
    }

}
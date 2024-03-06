<?php

namespace EntretienProfessionnel\Form\Recours;

trait RecoursFormAwareTrait
{
    private RecoursForm $recoursForm;

    public function getRecoursForm(): RecoursForm
    {
        return $this->recoursForm;
    }

    public function setRecoursForm(RecoursForm $recoursForm): void
    {
        $this->recoursForm = $recoursForm;
    }

}
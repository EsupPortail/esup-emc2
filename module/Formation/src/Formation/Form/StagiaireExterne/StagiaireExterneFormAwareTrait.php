<?php

namespace Formation\Form\StagiaireExterne;

trait StagiaireExterneFormAwareTrait {

    private StagiaireExterneForm $stagiaireExterneForm;

    public function getStagiaireExterneForm(): StagiaireExterneForm
    {
        return $this->stagiaireExterneForm;
    }

    public function setStagiaireExterneForm(StagiaireExterneForm $stagiaireExterneForm): void
    {
        $this->stagiaireExterneForm = $stagiaireExterneForm;
    }

}
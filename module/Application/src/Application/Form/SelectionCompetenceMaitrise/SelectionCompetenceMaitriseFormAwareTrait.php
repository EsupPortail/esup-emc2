<?php

namespace Application\Form\SelectionCompetenceMaitrise;

trait SelectionCompetenceMaitriseFormAwareTrait {

    /** @var SelectionCompetenceMaitriseForm */
    private $selectionCompetenceMaitriseForm;

    /**
     * @return SelectionCompetenceMaitriseForm
     */
    public function getSelectionCompetenceMaitriseForm(): SelectionCompetenceMaitriseForm
    {
        return $this->selectionCompetenceMaitriseForm;
    }

    /**
     * @param SelectionCompetenceMaitriseForm $selectionCompetenceMaitriseForm
     * @return SelectionCompetenceMaitriseForm
     */
    public function setSelectionCompetenceMaitriseForm(SelectionCompetenceMaitriseForm $selectionCompetenceMaitriseForm): SelectionCompetenceMaitriseForm
    {
        $this->selectionCompetenceMaitriseForm = $selectionCompetenceMaitriseForm;
        return $this->selectionCompetenceMaitriseForm;
    }

}
<?php

namespace Application\Form\SelectionMaitriseNiveau;

trait SelectionMaitriseNiveauFormAwareTrait {

    /** @var SelectionMaitriseNiveauForm */
    private $selectionMaitriseNiveauForm;

    /**
     * @return SelectionMaitriseNiveauForm
     */
    public function getSelectionMaitriseNiveauForm(): SelectionMaitriseNiveauForm
    {
        return $this->selectionMaitriseNiveauForm;
    }

    /**
     * @param SelectionMaitriseNiveauForm $selectionMaitriseNiveauForm
     * @return SelectionMaitriseNiveauForm
     */
    public function setSelectionMaitriseNiveauForm(SelectionMaitriseNiveauForm $selectionMaitriseNiveauForm): SelectionMaitriseNiveauForm
    {
        $this->selectionMaitriseNiveauForm = $selectionMaitriseNiveauForm;
        return $this->selectionMaitriseNiveauForm;
    }

}
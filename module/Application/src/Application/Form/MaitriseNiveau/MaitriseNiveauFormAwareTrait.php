<?php

namespace Application\Form\MaitriseNiveau;

trait MaitriseNiveauFormAwareTrait {

    /** @var MaitriseNiveauForm */
    private $MaitriseNiveauForm;

    /**
     * @return MaitriseNiveauForm
     */
    public function getMaitriseNiveauForm(): MaitriseNiveauForm
    {
        return $this->MaitriseNiveauForm;
    }

    /**
     * @param MaitriseNiveauForm $MaitriseNiveauForm
     * @return MaitriseNiveauForm
     */
    public function setMaitriseNiveauForm(MaitriseNiveauForm $MaitriseNiveauForm): MaitriseNiveauForm
    {
        $this->MaitriseNiveauForm = $MaitriseNiveauForm;
        return $this->MaitriseNiveauForm;
    }
}
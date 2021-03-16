<?php

namespace Application\Form\CompetenceMaitrise;

trait CompetenceMaitriseFormAwareTrait {

    /** @var CompetenceMaitriseForm */
    private $competenceMaitriseForm;

    /**
     * @return CompetenceMaitriseForm
     */
    public function getCompetenceMaitriseForm(): CompetenceMaitriseForm
    {
        return $this->competenceMaitriseForm;
    }

    /**
     * @param CompetenceMaitriseForm $competenceMaitriseForm
     * @return CompetenceMaitriseForm
     */
    public function setCompetenceMaitriseForm(CompetenceMaitriseForm $competenceMaitriseForm): CompetenceMaitriseForm
    {
        $this->competenceMaitriseForm = $competenceMaitriseForm;
        return $this->competenceMaitriseForm;
    }
}
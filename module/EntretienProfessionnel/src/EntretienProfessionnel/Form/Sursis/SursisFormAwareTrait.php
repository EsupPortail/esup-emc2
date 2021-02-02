<?php

namespace EntretienProfessionnel\Form\Sursis;

trait SursisFormAwareTrait {

    /** @var SursisForm $sursisForm */
    private $sursisForm;

    /**
     * @return SursisForm
     */
    public function getSursisForm(): SursisForm
    {
        return $this->sursisForm;
    }

    /**
     * @param SursisForm $sursisForm
     * @return SursisForm
     */
    public function setSursisForm(SursisForm $sursisForm): SursisForm
    {
        $this->sursisForm = $sursisForm;
        return $this->sursisForm;
    }
}
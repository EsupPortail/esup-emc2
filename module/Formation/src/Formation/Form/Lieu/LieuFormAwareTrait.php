<?php

namespace Formation\Form\Lieu;

trait LieuFormAwareTrait {

    private LieuForm $lieuForm;

    public function getLieuForm(): LieuForm
    {
        return $this->lieuForm;
    }

    public function setLieuForm(LieuForm $lieuForm): void
    {
        $this->lieuForm = $lieuForm;
    }

}
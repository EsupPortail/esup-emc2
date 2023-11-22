<?php

namespace Carriere\Form\Mobilite;

trait MobiliteFormAwareTrait {

    private MobiliteForm $mobiliteForm;

    public function getMobiliteForm() : MobiliteForm
    {
        return $this->mobiliteForm;
    }

    public function setMobiliteForm(MobiliteForm $mobiliteForm) : void
    {
        $this->mobiliteForm = $mobiliteForm;
    }
}
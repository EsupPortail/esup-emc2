<?php

namespace Formation\Form\EnqueteCategorie;

trait EnqueteCategorieFormAwareTrait {

    private EnqueteCategorieForm $enqueteCategorieForm;

    public function getEnqueteCategorieForm(): EnqueteCategorieForm
    {
        return $this->enqueteCategorieForm;
    }

    public function setEnqueteCategorieForm(EnqueteCategorieForm $enqueteCategorieForm): void
    {
        $this->enqueteCategorieForm = $enqueteCategorieForm;
    }


}
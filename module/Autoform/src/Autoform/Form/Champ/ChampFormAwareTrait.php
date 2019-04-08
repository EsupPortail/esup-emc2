<?php

namespace Autoform\Form\Champ;

trait ChampFormAwareTrait {

    /** @var ChampForm $categorieForm */
    private $champForm;

    /**
     * @return ChampForm
     */
    public function getChampForm()
    {
        return $this->champForm;
    }

    /**
     * @param ChampForm $champForm
     * @return ChampForm
     */
    public function setChampForm($champForm)
    {
        $this->champForm = $champForm;
        return $this->champForm;
    }


}
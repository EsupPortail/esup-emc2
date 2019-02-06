<?php

namespace Application\Form;

trait AutocompleteAwareTrait {

    private $autocomplete;

    /**
     * @return mixed
     */
    public function getAutocomplete()
    {
        return $this->autocomplete;
    }

    /**
     * @param mixed $autocomplete
     */
    public function setAutocomplete($autocomplete)
    {
        $this->autocomplete = $autocomplete;
    }
}
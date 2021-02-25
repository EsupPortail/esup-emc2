<?php

namespace UnicaenGlossaire\Form\Definition;

trait DefinitionFormAwareTrait {

    /** @var DefinitionForm */
    private $definitionForm;

    /**
     * @return DefinitionForm
     */
    public function getDefinitionForm(): DefinitionForm
    {
        return $this->definitionForm;
    }

    /**
     * @param DefinitionForm $definitionForm
     * @return DefinitionForm
     */
    public function setDefinitionForm(DefinitionForm $definitionForm): DefinitionForm
    {
        $this->definitionForm = $definitionForm;
        return $this->definitionForm;
    }

}
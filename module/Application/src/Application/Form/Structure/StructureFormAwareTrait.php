<?php

namespace Application\Form\Structure;

trait StructureFormAwareTrait {

    /** @var StructureForm $structureForm */
    private $structureForm;

    /**
     * @return StructureForm
     */
    public function getStructureForm()
    {
        return $this->structureForm;
    }

    /**
     * @param StructureForm $structureForm
     * @return StructureForm
     */
    public function setStructureForm($structureForm)
    {
        $this->structureForm = $structureForm;
        return $this->structureForm;
    }


}
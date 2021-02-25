<?php

namespace UnicaenGlossaire\Service\Definition;

trait DefinitionServiceAwareTrait {

    /** @var DefinitionService */
    private $definitionService;

    /**
     * @return DefinitionService
     */
    public function getDefinitionService(): DefinitionService
    {
        return $this->definitionService;
    }

    /**
     * @param DefinitionService $definitionService
     * @return DefinitionService
     */
    public function setDefinitionService(DefinitionService $definitionService): DefinitionService
    {
        $this->definitionService = $definitionService;
        return $this->definitionService;
    }

}
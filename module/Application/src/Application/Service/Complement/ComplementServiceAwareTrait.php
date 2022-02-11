<?php

namespace Application\Service\Complement;

trait ComplementServiceAwareTrait {

    /** @var ComplementService */
    private $complementService;

    /**
     * @return ComplementService
     */
    public function getComplementService(): ComplementService
    {
        return $this->complementService;
    }

    /**
     * @param ComplementService $complementService
     * @return ComplementService
     */
    public function setComplementService(ComplementService $complementService): ComplementService
    {
        $this->complementService = $complementService;
        return $this->complementService;
    }


}
<?php

namespace Application\Entity\Db\MacroContent;

use Application\Entity\Db\Structure;

trait StructureMacroTrait {

    public function toStringDenomination() : string
    {
        /** @var Structure $structure */
        $structure = $this;
        $texte = $structure->getLibelleLong();
        return $texte;
    }
    public function toStringDescription() : string
    {
        /** @var Structure $structure */
        $structure = $this;
        $texte = "";
        if ($structure->getRepriseResumeMere()) {
            $texte .= $structure->getParent()->toStringDescription();
        }
        $texte .= $structure->toStringDenomination();
        $texte .= $structure->getDescription();
        return $texte;
    }

    public function toStringStructureBloc() : string
    {
        /** @var Structure $structure */
        $structure = $this;
        $texte = "";
        if ($structure->getRepriseResumeMere()) {
            $texte .= $structure->getParent()->toStringStructureBloc();
        }
        $texte .= "<h3>" . $structure->toStringDenomination() . "</h3>";
        $texte .= $structure->getDescription(false);
        return $texte;
    }

}
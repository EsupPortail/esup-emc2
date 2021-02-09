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
        $texte = $structure->getDescription($structure->getRepriseResumeMere());
        return $texte;
    }

    public function toStringStructureBloc() : string
    {
        $texte  = "";
//        $texte .= "<table class='structure-bloc'>";
//        $texte .= "<tr><th>";
        $texte .= "<h3>";
        $texte .= $this->toStringDenomination();
        $texte .= "</h3>";
//        $texte .= "</th></tr>";
//        $texte .= "<tr><td>";
        $texte .= $this->toStringDescription();
//        $texte .= "</td></tr>";
//        $texte .= "</table>";
        return $texte;
    }

}
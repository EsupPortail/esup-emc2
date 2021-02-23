<?php

namespace Application\Entity\Db\MacroContent;


use Application\Entity\Db\MissionSpecifique;

trait MissionSpecifiqueMacroTrait {

    public function toStringDescription()
    {
        /** @var MissionSpecifique $mission */
        $mission = $this;
        if ($mission->getDescription() === null ) {
            return "Aucune description fourni pour cette mission spécifique";
        }
        return $mission->getDescription();
    }
}
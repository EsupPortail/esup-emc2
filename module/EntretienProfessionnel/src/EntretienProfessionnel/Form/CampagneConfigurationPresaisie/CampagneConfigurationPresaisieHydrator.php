<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationPresaisie;

use EntretienProfessionnel\Entity\Db\CampagneConfigurationPresaisie;
use Laminas\Hydrator\HydratorInterface;
use UnicaenAutoform\Service\Champ\ChampServiceAwareTrait;
use UnicaenAutoform\Service\Formulaire\FormulaireServiceAwareTrait;
use UnicaenRenderer\Service\Macro\MacroServiceAwareTrait;

class CampagneConfigurationPresaisieHydrator implements HydratorInterface
{

    use FormulaireServiceAwareTrait;
    use ChampServiceAwareTrait;
    use MacroServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var CampagneConfigurationPresaisie $object */
        $data = [
            'formulaire' => $object->getFormulaire()?->getId(),
            'champ' => $object->getChamp()?->getId(),
            'macro' => $object->getMacro()?->getId(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $formulaire = (isset($data['formulaire']) and $data['formulaire'] !== '') ? $this->getFormulaireService()->getFormulaire($data['formulaire']) : null;
        $champ = (isset($data['champ']) and $data['champ'] !== '') ? $this->getChampService()->getChamp($data['champ']) : null;
        $macro = (isset($data['macro']) and $data['macro'] !== '') ? $this->getMacroService()->getMacro($data['macro']) : null;
        $description = (isset($data['description']) and $data['description'] !== '') ? $data['description'] : null;

        /** @var CampagneConfigurationPresaisie $object */
        $object->setFormulaire($formulaire);
        $object->setChamp($champ);
        $object->setMacro($macro);
        $object->setDescription($description);
        return $object;
    }
}

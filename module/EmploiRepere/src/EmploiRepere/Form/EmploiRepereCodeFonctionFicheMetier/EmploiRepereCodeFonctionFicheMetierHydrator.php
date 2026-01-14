<?php

namespace EmploiRepere\Form\EmploiRepereCodeFonctionFicheMetier;

use EmploiRepere\Entity\Db\EmploiRepereCodeFonctionFicheMetier;
use EmploiRepere\Service\EmploiRepere\EmploiRepereServiceAwareTrait;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class EmploiRepereCodeFonctionFicheMetierHydrator implements HydratorInterface
{
    use CodeFonctionServiceAwareTrait;
    use EmploiRepereServiceAwareTrait;
    use FicheMetierServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var EmploiRepereCodeFonctionFicheMetier $object */
        $data = [
            'emploi-repere' => $object?->getEmploiRepere()?->getId(),
            'code-fonction' => $object?->getCodeFonction()?->getId(),
            'fiche-metier' => $object?->getFicheMetier()?->getId(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $emploiRepere = (isset($data['emploi-repere'])) ? $this->getEmploiRepereService()->getEmploiRepere($data['emploi-repere']) : null;
        $codeFonction = (isset($data['code-fonction'])) ? $this->getCodeFonctionService()->getCodeFonction($data['code-fonction']) : null;
        $ficheMetier = (isset($data['fiche-metier'])) ? $this->getFicheMetierService()->getFicheMetier($data['fiche-metier']) : null;

        /** @var EmploiRepereCodeFonctionFicheMetier $object */
        $object->setEmploiRepere($emploiRepere);
        $object->setCodeFonction($codeFonction);
        $object->setFicheMetier($ficheMetier);
        return $object;
    }
}
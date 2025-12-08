<?php

namespace FicheMetier\Form\CodeFonction;

use Application\Entity\Db\FichePoste;
use Carriere\Service\NiveauFonction\NiveauFonctionServiceAwareTrait;
use FicheMetier\Entity\Db\CodeFonction;
use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Hydrator\HydratorInterface;
use Metier\Entity\Db\FamilleProfessionnelle;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;

class CodeFonctionHydrator implements HydratorInterface
{
    use FamilleProfessionnelleServiceAwareTrait;
    use NiveauFonctionServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var CodeFonction $codeFonction */
        $data = [
            'famille' => $object->getFamille()?->getId(),
            'niveau' => $object->getNiveau()?->getId(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $famille = (isset($data['famille']))? $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($data['famille']): null;
        $niveau  = (isset($data['niveau']))? $this->getNiveauFonctionService()->getNiveauFonction($data['niveau']): null;


        /** @var CodeFonction $codeFonction */
        $object->setFamille($famille);
        $object->setNiveau($niveau);
        return $object;
    }

}
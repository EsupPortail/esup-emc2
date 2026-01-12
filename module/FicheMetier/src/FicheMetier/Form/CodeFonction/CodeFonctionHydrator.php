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
        /** @var CodeFonction $object */
        $data = [
            'famille' => $object->getFamilleProfessionnelle()?->getId(),
            'niveau_fonction' => $object->getNiveauFonction()?->getId(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $famille = (isset($data['famille']))? $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($data['famille']): null;
        $niveau  = (isset($data['niveau_fonction']))? $this->getNiveauFonctionService()->getNiveauFonction($data['niveau_fonction']): null;


        /** @var CodeFonction $object */
        $object->setFamilleProfessionnelle($famille);
        $object->setNiveauFonction($niveau);
        return $object;
    }

}
<?php

namespace Application\Form\CompetenceElement;

use Application\Entity\Db\ApplicationElement;
use Application\Entity\Db\CompetenceElement;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class CompetenceElementHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;

    /**
     * @param CompetenceElement $object
     * @return array
     */
    public function extract($object)
    {
        $commentaires = $object->getCommentaire();
        $niveau = null;
        $annee = null;
        if ($commentaires !== null) {
            $split = explode(" - ",$commentaires);
            $niveau = $split[1];
            $annee = $split[0];
        }

        $data = [
            'competence'   => ($object->getCompetence())?$object->getCompetence()->getId():null,
            'niveau'       => $niveau,
            'annee'        => $annee,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param CompetenceElement $object
     * @return CompetenceElement
     */
    public function hydrate(array $data, $object)
    {
        $competence = isset($data['competence'])?$this->getCompetenceService()->getCompetence($data['competence']):null;
        $niveau = isset($data['niveau'])?$data['niveau']:"Débutant";
        $annee= (isset($data['annee']) AND $data['annee'] !== "")? ((int) $data['annee']):null;
        $commentaire = $annee . " - " . $niveau;

        $object->setCompetence($competence);
        $object->setCommentaire($commentaire);

        return $object;
    }
}
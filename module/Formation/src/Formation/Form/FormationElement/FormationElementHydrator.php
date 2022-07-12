<?php

namespace Formation\Form\FormationElement;

use Formation\Entity\Db\FormationElement;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class FormationElementHydrator implements HydratorInterface {
    use FormationServiceAwareTrait;

    /**
     * @param FormationElement $object
     * @return array
     */
    public function extract($object): array
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
            'formation'   => ($object->getFormation())?$object->getFormation()->getId():null,
            'niveau'       => $niveau,
            'annee'        => $annee,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FormationElement $object
     * @return FormationElement
     */
    public function hydrate(array $data, $object)
    {
        $formation = isset($data['formation'])?$this->getFormationService()->getFormation($data['formation']):null;
        $niveau = isset($data['niveau'])?$data['niveau']:"Débutant";
        $annee= (isset($data['annee']) AND $data['annee'] !== "")? ($data['annee']):null;
        $commentaire = $annee . " - " . $niveau;

        $object->setFormation($formation);
        $object->setCommentaire($commentaire);

        return $object;
    }
}
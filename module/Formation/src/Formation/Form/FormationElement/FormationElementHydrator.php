<?php

namespace Formation\Form\FormationElement;

use Formation\Entity\Db\FormationElement;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class FormationElementHydrator implements HydratorInterface {
    use FormationServiceAwareTrait;

    public function extract($object): array
    {
        /** @var  FormationElement $object */
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

    public function hydrate(array $data, $object): object
    {
        $formation = isset($data['formation'])?$this->getFormationService()->getFormation($data['formation']):null;
        $niveau = $data['niveau'] ?? "Débutant";
        $annee= (isset($data['annee']) AND $data['annee'] !== "")? ($data['annee']):null;
        $commentaire = $annee . " - " . $niveau;

        /** @var  FormationElement $object */
        $object->setFormation($formation);
        $object->setCommentaire($commentaire);

        return $object;
    }
}
<?php

namespace Metier\Form\MetierNiveau;

use Metier\Entity\Db\MetierNiveau;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class MetierNiveauHydrator implements HydratorInterface {
    use MetierServiceAwareTrait;

    /**
     * @param MetierNiveau $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'metier' => $object->getMetier()->getId(),
            'borne_inferieure' => $object->getBorneInferieure(),
            'borne_superieure' => $object->getBorneSuperieure(),
            'valeur_recommandee' => $object->getValeurRecommandee(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param MetierNiveau $object
     * @return MetierNiveau
     */
    public function hydrate(array $data, $object)
    {
        $metier = isset($data['metier'])?$this->getMetierService()->getMetier($data['metier']):null;
        $borneInferieure = isset($data['borne_inferieure'])?$data['borne_inferieure']:null;
        $borneSuperieure = isset($data['borne_superieure'])?$data['borne_superieure']:null;
        $valeurRecommandee = isset($data['valeur_recommandee'])?$data['valeur_recommandee']:null;
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;

        $object->setMetier($metier);
        $object->setBorneInferieure($borneInferieure);
        $object->setBorneSuperieure($borneSuperieure);
        $object->setValeurRecommandee($valeurRecommandee);
        $object->setDescription($description);

        return $object;
    }
}
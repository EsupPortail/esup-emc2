<?php

namespace Metier\Form\MetierNiveau;

use Application\Service\Niveau\NiveauServiceAwareTrait;
use Metier\Entity\Db\MetierNiveau;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class MetierNiveauHydrator implements HydratorInterface {
    use NiveauServiceAwareTrait;
    use MetierServiceAwareTrait;

    /**
     * @param MetierNiveau $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'metier' => $object->getMetier()->getId(),
            'borne_inferieure' => ($object->getBorneInferieure())?$object->getBorneInferieure()->getId():null,
            'borne_superieure' => ($object->getBorneSuperieure())?$object->getBorneSuperieure()->getId():null,
            'valeur_recommandee' => ($object->getValeurRecommandee())?$object->getValeurRecommandee()->getId():null,
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
        $borneInferieure = isset($data['borne_inferieure'])?$this->getNiveauService()->getNiveau($data['borne_inferieure']):null;
        $borneSuperieure = isset($data['borne_superieure'])?$this->getNiveauService()->getNiveau($data['borne_superieure']):null;
        $valeurRecommandee = isset($data['valeur_recommandee'])?$this->getNiveauService()->getNiveau($data['valeur_recommandee']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;

        $object->setMetier($metier);
        $object->setBorneInferieure($borneInferieure);
        $object->setBorneSuperieure($borneSuperieure);
        $object->setValeurRecommandee($valeurRecommandee);
        $object->setDescription($description);

        return $object;
    }
}
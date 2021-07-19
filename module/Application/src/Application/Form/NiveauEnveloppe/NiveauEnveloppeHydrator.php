<?php

namespace Application\Form\NiveauEnveloppe;

use Application\Entity\Db\NiveauEnveloppe;
use Application\Service\Niveau\NiveauServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class NiveauEnveloppeHydrator implements HydratorInterface {
    use NiveauServiceAwareTrait;

    /**
     * @param NiveauEnveloppe $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'borne_inferieure' => ($object->getBorneInferieure())?$object->getBorneInferieure()->getId():null,
            'borne_superieure' => ($object->getBorneSuperieure())?$object->getBorneSuperieure()->getId():null,
            'valeur_recommandee' => ($object->getValeurRecommandee())?$object->getValeurRecommandee()->getId():null,
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param NiveauEnveloppe $object
     * @return NiveauEnveloppe
     */
    public function hydrate(array $data, $object)
    {
        $borneInferieure = isset($data['borne_inferieure'])?$this->getNiveauService()->getNiveau($data['borne_inferieure']):null;
        $borneSuperieure = isset($data['borne_superieure'])?$this->getNiveauService()->getNiveau($data['borne_superieure']):null;
        $valeurRecommandee = (isset($data['valeur_recommandee']) AND $data['valeur_recommandee'] !== '')?$this->getNiveauService()->getNiveau($data['valeur_recommandee']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;

        $object->setBorneInferieure($borneInferieure);
        $object->setBorneSuperieure($borneSuperieure);
        $object->setValeurRecommandee($valeurRecommandee);
        $object->setDescription($description);

        return $object;
    }
}
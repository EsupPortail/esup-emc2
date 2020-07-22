<?php

namespace Application\Form\Metier;

use Application\Entity\Db\Metier;
use Application\Service\Categorie\CategorieServiceAwareTrait;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class MetierHydrator implements HydratorInterface {
    use CategorieServiceAwareTrait;
    use DomaineServiceAwareTrait;

    /**
     * @param Metier $object
     * @return array
     */
    public function extract($object)
    {
        $domaineIds = [];
        $domaines = $object->getDomaines();
        if ($domaines) {
            foreach ($domaines as $domaine) $domaineIds[] = $domaine->getId();
        }

        $data = [
            'categorie' => ($object->getCategorie())?$object->getCategorie()->getId():null,
            'domaines' => $domaineIds,
            'libelle' => $object->getLibelle(),
            'niveau' => $object->getNiveau(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Metier $object
     * @return Metier
     */
    public function hydrate(array $data, $object)
    {
        $categorie = (isset($data['categorie']) AND trim($data['categorie']) !== '')?$this->getCategorieService()->getCategorie($data['categorie']):null;
        $niveau = (isset($data['niveau']) AND trim($data['niveau']) !== "")?$data['niveau']:null;

        $object->clearDomaines();
        foreach ($data['domaines'] as $id) {
            $domaine = $this->getDomaineService()->getDomaine($id);
            if ($domaine) $object->addDomaine($domaine);
        }

        $object->setLibelle($data['libelle']);
        $object->setCategorie($categorie);
        $object->setNiveau($niveau);

        return $object;
    }

}
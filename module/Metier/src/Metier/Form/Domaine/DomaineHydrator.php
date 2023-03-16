<?php

namespace Metier\Form\Domaine;

use Metier\Entity\Db\Domaine;
use Metier\Entity\Db\FamilleProfessionnelle;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class DomaineHydrator implements HydratorInterface {
    use FamilleProfessionnelleServiceAwareTrait;

    /**
     * @param Domaine $object
     * @return array
     */
    public function extract($object)  : array
    {
        $familles_id = array_map(function (FamilleProfessionnelle $a) { return $a->getId();} , $object->getFamilles());
        $data = [
            'libelle' => $object->getLibelle(),
            'famille' => $familles_id,
            'fonction' => $object->getTypeFonction(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Domaine $object
     * @return Domaine
     */
    public function hydrate(array $data, $object) : object
    {
        $object->clearFamilles();
        foreach ($data['famille'] as $id) {
            $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelle($id);
            if ($famille) $object->addFamille($famille);
        }

        $libelle = (isset($data['libelle']) AND trim($data['libelle']))?trim($data['libelle']):null;
        $fonction = (isset($data['fonction']) AND trim($data['fonction']))?trim($data['fonction']):null;
        $object->setLibelle($libelle);
        $object->setTypeFonction($fonction);

        return $object;
    }

}
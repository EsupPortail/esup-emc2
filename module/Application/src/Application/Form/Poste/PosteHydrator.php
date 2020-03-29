<?php

namespace Application\Form\Poste;

use Application\Entity\Db\Poste;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class PosteHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    use StructureServiceAwareTrait;
    use DomaineServiceAwareTrait;

    /**
     * @param Poste $object
     * @return array
     */
    public function extract($object)
    {

        $data = [
            'numero_poste'      => $object->getNumeroPoste(),
            'structure'         => ($object->getStructure())?['id' => $object->getStructure()->getId(), 'label' => $object->getStructure()->getLibelleLong()]:null,
            'correspondance'    => ($object->getCorrespondance())?$object->getCorrespondance()->getId():null,
            'rattachement'      => ($object->getRattachementHierarchique())?['id' => $object->getRattachementHierarchique()->getId(), 'label' => $object->getRattachementHierarchique()->getDenomination()]:null,
            'domaine'           => ($object->getDomaine())?$object->getDomaine()->getId():null,
            'fonction'          => $object->getFonction(),
            'lien'              => $object->getLien(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Poste $object
     * @return Poste
     */
    public function hydrate(array $data, $object)
    {
        $structure = $this->getStructureService()->getStructure($data['structure']['id']);
        $correspondance = $this->getRessourceRhService()->getCorrespondance($data['correspondance']);
        $rattachement = $this->getAgentService()->getAgent($data['rattachement']['id']);
        $domaine = $this->getDomaineService()->getDomaine($data['domaine']);

        $object->setNumeroPoste($data['numero_poste']);
        $object->setStructure($structure);
        $object->setCorrespondance($correspondance);
        $object->setRattachementHierarchique($rattachement);
        $object->setDomaine($domaine);
        $object->setFonction($data['fonction']);
        $object->setLien($data['lien']);

        return $object;
    }

}

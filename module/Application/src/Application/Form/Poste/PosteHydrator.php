<?php

namespace Application\Form\Poste;

use Application\Entity\Db\Poste;
use Application\Service\Agent\AgentServiceAwareTrait;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class PosteHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use StructureServiceAwareTrait;

    /**
     * @param Poste $object
     * @return array
     */
    public function extract($object): array
    {

        $data = [
            'numero_poste'      => $object->getNumeroPoste(),
            'structure'         => ($object->getStructure())?['id' => $object->getStructure()->getId(), 'label' => $object->getStructure()->getLibelleLong()]:null,
            'correspondance'    => ($object->getCorrespondance())?$object->getCorrespondance()->getId():null,
            'rattachement'      => ($object->getRattachementHierarchique())?['id' => $object->getRattachementHierarchique()->getId(), 'label' => $object->getRattachementHierarchique()->getDenomination()]:null,
            'domaine'           => ($object->getDomaine())?$object->getDomaine()->getId():null,
            'fonction'          => $object->getFonction(),
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
        $correspondance = $this->getCorrespondanceService()->getCorrespondance($data['correspondance']);
        $rattachement = $this->getAgentService()->getAgent($data['rattachement']['id']);
        $domaine = $this->getDomaineService()->getDomaine($data['domaine']);

        $object->setNumeroPoste($data['numero_poste']);
        $object->setStructure($structure);
        $object->setCorrespondance($correspondance);
        $object->setRattachementHierarchique($rattachement);
        $object->setDomaine($domaine);
        $object->setFonction($data['fonction']);

        return $object;
    }

}

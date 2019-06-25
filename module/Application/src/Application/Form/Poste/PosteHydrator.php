<?php

namespace Application\Form\Poste;

use Application\Entity\Db\Poste;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Domaine\DomaineServiceAwareTrait;
use Application\Service\Fonction\FonctionServiceAwareTrait;
use Application\Service\Immobilier\ImmobilierServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class PosteHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use FonctionServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    use ImmobilierServiceAwareTrait;
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
            'localisation'      => ($object->getLocalisation())?$object->getLocalisation()->getId():null,
            'structure'         => ($object->getStructure())?$object->getStructure()->getId():null,
            'correspondance'    => ($object->getCorrespondance())?$object->getCorrespondance()->getId():null,
            'rattachement'      => ($object->getRattachementHierarchique())?$object->getRattachementHierarchique()->getId():null,
            'domaine'           => ($object->getDomaine())?$object->getDomaine()->getId():null,
            'fonction'          => ($object->getFonction())?$object->getFonction()->getId():null,
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
        $structure = $this->getStructureService()->getStructure($data['structure']);
        $correspondance = $this->getRessourceRhService()->getCorrespondance($data['correspondance']);
        $rattachement = $this->getAgentService()->getAgent($data['rattachement']);
        $domaine = $this->getDomaineService()->getDomaine($data['domaine']);
        $fonction = $this->getFonctionService()->getFonction($data['fonction']);
        $batiment = $this->getImmobilierService()->getBatiment($data['localisation']);

        $object->setNumeroPoste($data['numero_poste']);
        $object->setLocalisation($batiment);
        $object->setStructure($structure);
        $object->setCorrespondance($correspondance);
        $object->setRattachementHierarchique($rattachement);
        $object->setDomaine($domaine);
        $object->setFonction($fonction);
        $object->setLien($data['lien']);

        return $object;
    }

}
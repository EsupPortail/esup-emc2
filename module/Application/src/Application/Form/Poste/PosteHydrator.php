<?php

namespace Application\Form\Poste;

use Application\Entity\Db\Poste;
use Application\Service\Affectation\AffectationAwareServiceTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class PosteHydrator implements HydratorInterface {
    use AffectationAwareServiceTrait;
    use AgentServiceAwareTrait;
    use RessourceRhServiceAwareTrait;

    /**
     * @param Poste $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'numero_poste'      => $object->getNumeroPoste(),
            'localisation'      => $object->getLocalisation(),
            'affectation'       => ($object->getAffectation())?$object->getAffectation()->getId():null,
            'correspondance'    => ($object->getCorrespondance())?$object->getCorrespondance()->getId():null,
            'rattachement'      => ($object->getRattachementHierarchique())?$object->getRattachementHierarchique()->getId():null,
            'domaine'           => ($object->getDomaine())?$object->getDomaine()->getId():null,
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
        $affectation = $this->getAffectationService()->getAffectation($data['affectation']);
        $correspondance = $this->getRessourceRhService()->getCorrespondance($data['correspondance']);
        $rattachement = $this->getAgentService()->getAgent($data['rattachement']);
        $domaine = $this->getRessourceRhService()->getDomaine($data['domaine']);

        $object->setNumeroPoste($data['numero_poste']);
        $object->setLocalisation($data['localisation']);
        $object->setAffectation($affectation);
        $object->setCorrespondance($correspondance);
        $object->setRattachementHierarchique($rattachement);
        $object->setDomaine($domaine);

        return $object;
    }

}
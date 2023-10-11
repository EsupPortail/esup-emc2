<?php

namespace Formation\Form\Abonnement;

use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Formation\Entity\Db\FormationAbonnement;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class AbonnementHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use FormationServiceAwareTrait;

    /**
     * @param FormationAbonnement $object
     * @return array
     */
    public function extract(object $object): array
    {
        $data = [
            'agent' =>
                ($object AND $object->getAgent()) ? [
                'id' => $object->getAgent()->getId() ,
                'libelle' => $object->getAgent()->getDenomination() ,
                ] : null,
            'formation' => ($object AND $object->getFormation()) ? $object->getFormation()->getId() : null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FormationAbonnement $object
     * @return FormationAbonnement
     */
    public function hydrate(array $data, object $object): object
    {
        $agent = (isset($data['agent']) AND isset($data['agent']['id']))?$this->getAgentService()->getAgent($data['agent']['id']):null;
        $formation = (isset($data['formation']))?$this->getFormationService()->getFormation($data['formation']):null;

        $object->setAgent($agent);
        $object->setFormation($formation);
        $object->setDateInscription(new DateTime());

        return $object;
    }


}
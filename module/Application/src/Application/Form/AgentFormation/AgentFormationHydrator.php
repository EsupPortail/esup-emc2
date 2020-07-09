<?php

namespace Application\Form\AgentFormation;

use Application\Entity\Db\AgentFormation;
use Application\Service\Formation\FormationServiceAwareTrait;
use DateTime;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use Zend\Hydrator\HydratorInterface;

class AgentFormationHydrator implements HydratorInterface {
    use FormationServiceAwareTrait;
    use DateTimeAwareTrait;

    /**
     * @param AgentFormation $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'formation' => ($object->getFormation())?$object->getFormation()->getId():null,
            'date' => ($object->getDate())?$object->getDate()->format('Y-m-d'):null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param AgentFormation $object
     * @return AgentFormation
     */
    public function hydrate(array $data, $object)
    {
        $formation = isset($data['formation'])?$this->getFormationService()->getFormation($data['formation']):null;
        $date = isset($data['date'])?DateTime::createFromFormat("Y-m-d",$data['date']):$this->getDateTime();

        $object->setFormation($formation);
        $object->setDate($date);

        return $object;
    }
}
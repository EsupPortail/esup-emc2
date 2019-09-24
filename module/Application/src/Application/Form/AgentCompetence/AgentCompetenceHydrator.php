<?php

namespace Application\Form\AgentCompetence;

use Application\Entity\Db\AgentCompetence;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class AgentCompetenceHydrator implements HydratorInterface {
    use CompetenceServiceAwareTrait;

    /**
     * @param AgentCompetence $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'competence' => ($object->getCompetence())?$object->getCompetence()->getId():null,
            'niveau' => $object->getNiveau(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param AgentCompetence $object
     * @return AgentCompetence
     */
    public function hydrate(array $data, $object)
    {
        $competence = null;
        if (isset($data['competence']) AND $data['competence'] != '') $competence = $this->getCompetenceService()->getCompetence($data['competence']);
        $object->setCompetence($competence);

        $niveau = null;
        if (isset($data['niveau']) AND $data['niveau'] != '') $niveau = $data['niveau'];
        $object->setNiveau($niveau);

        return $object;
    }

}
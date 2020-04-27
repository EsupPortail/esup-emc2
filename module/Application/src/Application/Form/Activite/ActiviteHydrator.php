<?php

namespace Application\Form\Activite;

use Application\Entity\Db\Activite;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Application\Service\Competence\CompetenceServiceAwareTrait;
use Application\Service\Formation\FormationServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ActiviteHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use FormationServiceAwareTrait;

    /**
     * @param Activite $object
     * @return array
     */
    public function extract($object)
    {
//        $applicationIds = [];
//        foreach ($object->getApplications() as $application) {
//            $applicationIds[] = $application->getId();
//        }
//
//        $competenceIds = [];
//        foreach ($object->getCompetences() as $competence) {
//            $competenceIds[] = $competence->getId();
//        }
//
//        $formationIds = [];
//        foreach ($object->getFormations() as $formation) {
//            $formationIds[] = $formation->getId();
//        }

        $data = [
//            'applications' => $applicationIds,
//            'competences' => $competenceIds,
//            'formations' => $formationIds,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Activite $object
     * @return Activite
     */
    public function hydrate(array $data, $object)
    {
//        $object->clearApplications();
//        if (isset($data['applications'])) {
//            foreach ($data['applications'] as $id) {
//                $application = $this->getApplicationService()->getApplication($id);
//                if ($application) $object->addApplication($application);
//            }
//        }
//
//        $object->clearCompetences();
//        if (isset($data['competences'])) {
//            foreach ($data['competences'] as $id) {
//                $competence = $this->getCompetenceService()->getCompetence($id);
//                if ($competence) $object->addCompetence($competence);
//            }
//        }
//
//        $object->clearFormations();
//        if (isset($data['formations'])) {
//            foreach ($data['formations'] as $id) {
//                $formation = $this->getFormationService()->getFormation($id);
//                if ($formation) $object->addFormation($formation);
//            }
//        }
        return $object;
    }

}
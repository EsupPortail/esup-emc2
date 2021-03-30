<?php

namespace Application\Form\SelectionCompetenceMaitrise;

use Application\Entity\Db\Interfaces\HasNiveauMaitriseInterface;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class SelectionCompetenceMaitriseHydrator implements HydratorInterface {
    use CompetenceMaitriseServiceAwareTrait;

    /**
     * @param HasNiveauMaitriseInterface $object
     * @return array|void
     */
    public function extract($object)
    {
        $data = [
            'niveau' => ($object->getNiveauMaitrise())?$object->getNiveauMaitrise()->getId():null,
            'clef' => ($object->isClef()),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param HasNiveauMaitriseInterface $object
     * @return HasNiveauMaitriseInterface
     */
    public function hydrate(array $data, $object)
    {
        $niveau = $this->getCompetenceMaitriseService()->getCompetenceMaitrise(isset($data['niveau'])?$data['niveau']:null);
        $clef = (isset($data['clef']))? ($data['clef'] == 1) : null;
        $object->setNiveauMaitrise($niveau);
        $object->setClef($clef);
        return $object;
    }
}
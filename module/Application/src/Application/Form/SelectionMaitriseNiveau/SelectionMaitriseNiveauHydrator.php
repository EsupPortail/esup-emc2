<?php

namespace Application\Form\SelectionMaitriseNiveau;

use Application\Entity\Db\Interfaces\HasNiveauMaitriseInterface;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class SelectionMaitriseNiveauHydrator implements HydratorInterface {
    use NiveauServiceAwareTrait;

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
        $niveau = $this->getNiveauService()->getMaitriseNiveau(isset($data['niveau'])?$data['niveau']:null);
        $object->setNiveauMaitrise($niveau);
        $clef = (isset($data['clef']))? ($data['clef'] == 1) : null;
        $object->setClef($clef);
        return $object;
    }
}
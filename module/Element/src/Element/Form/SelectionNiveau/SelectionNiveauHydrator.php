<?php

namespace Element\Form\SelectionNiveau;

use Element\Entity\Db\Interfaces\HasNiveauMaitriseInterface;
use Element\Service\NiveauMaitrise\NiveauMaitriseServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionNiveauHydrator implements HydratorInterface {
    use NiveauMaitriseServiceAwareTrait;

    /**
     * @param HasNiveauMaitriseInterface $object
     * @return array
     */
    public function extract($object): array
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
    public function hydrate(array $data, $object): object
    {
        $niveau = $this->getNiveauMaitriseService()->getMaitriseNiveau($data['niveau'] ?? null);
        $object->setNiveauMaitrise($niveau);

        if (isset($data['clef'])) {
            $clef = (isset($data['clef'])) ? ($data['clef'] == 1) : null;
            $object->setClef($clef);
        }
        return $object;
    }
}
<?php

namespace Element\Form\SelectionNiveau;

use Element\Entity\Db\Interfaces\HasNiveauInterface;
use Element\Service\Niveau\NiveauServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionNiveauHydrator implements HydratorInterface {
    use NiveauServiceAwareTrait;

    /**
     * @param HasNiveauInterface $object
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
     * @param HasNiveauInterface $object
     * @return HasNiveauInterface
     */
    public function hydrate(array $data, $object): object
    {
        $niveau = $this->getNiveauService()->getMaitriseNiveau($data['niveau'] ?? null);
        $object->setNiveauMaitrise($niveau);

        if (isset($data['clef'])) {
            $clef = (isset($data['clef'])) ? ($data['clef'] == 1) : null;
            $object->setClef($clef);
        }
        return $object;
    }
}
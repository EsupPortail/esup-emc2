<?php

namespace Application\Form\ApplicationElement;

use Application\Entity\Db\ApplicationElement;
use Application\Service\Application\ApplicationServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class ApplicationElementHydrator implements HydratorInterface {
    use ApplicationServiceAwareTrait;

    /**
     * @param ApplicationElement $object
     * @return array
     */
    public function extract($object)
    {
        $commentaires = $object->getCommentaire();
        $type = null;
        $annee = null;
        if ($commentaires !== null) {
            $split = explode(" - ",$commentaires);
            $type = $split[1];
            $annee = $split[0];
        }

        $data = [
            'application'   => ($object->getApplication())?$object->getApplication()->getId():null,
            'type'          => $type,
            'annee'         => $annee,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ApplicationElement $object
     * @return ApplicationElement
     */
    public function hydrate(array $data, $object)
    {
        $application = isset($data['application'])?$this->getApplicationService()->getApplication($data['application']):null;
        $type = isset($data['type'])?$data['type']:"Auto-formation";
        $annee= (isset($data['annee']) AND $data['annee'] !== "")? ((int) $data['annee']):null;
        $commentaire = $annee . " - " . $type;

        $object->setApplication($application);
        $object->setCommentaire($commentaire);

        return $object;
    }
}
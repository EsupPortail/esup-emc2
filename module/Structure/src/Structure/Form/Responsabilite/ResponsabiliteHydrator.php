<?php

namespace Structure\Form\Responsabilite;

use Agent\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Laminas\Hydrator\HydratorInterface;
use RuntimeException;
use Structure\Entity\Db\StructureGestionnaire;
use Structure\Entity\Db\StructureResponsable;
use Structure\Service\Structure\StructureServiceAwareTrait;

class ResponsabiliteHydrator implements HydratorInterface
{
    use StructureServiceAwareTrait;
    use AgentServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var  StructureResponsable|StructureGestionnaire $object */
        switch ($object) {
            case $object instanceof StructureGestionnaire :
            case $object instanceof StructureResponsable :
                $responsable = $object->getAgent();
                break;
            default : throw new RuntimeException("ResponsabiliteHydrator::extract : Type d'objet inconnu [" . get_class($object) . "]");
        }

        $data = [
            'structure' => ($object->getStructure())?['id' => $object->getStructure()->getId(), 'label' => $object->getStructure()->getLibelleLong()] :null,
            'responsable' => ($responsable)?['id' => $responsable->getId(), 'label' => $responsable->getDenomination()] :null,
            'date_debut' => ($object->getDateDebut())?$object->getDateDebut()->format('Y-m-d'):null,
            'date_fin' => ($object->getDateFin())?$object->getDateFin()->format('Y-m-d'):null,
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $structure = (isset($data['structure']['id']) AND trim($data['structure']['id']) !== '')?$this->getStructureService()->getStructure($data['structure']['id']):null;
        $responsable = (isset($data['responsable']['id']) AND trim($data['responsable']['id']) !== '')?$this->getAgentService()->getAgent($data['responsable']['id']):null;
        $dateDebut = (isset($data['date_debut']) AND $data['date_debut'] !== '') ? DateTime::createFromFormat('Y-m-d H:i:s', $data['date_debut']. " 08:00:00") :null;
        $dateFin = (isset($data['date_fin']) AND $data['date_fin'] !== '') ? DateTime::createFromFormat('Y-m-d H:i:s', $data['date_fin']. " 18:00:00") :null;

        /** @var  StructureResponsable|StructureGestionnaire $object */
        $object->setStructure($structure);
        $object->setDateDebut($dateDebut);
        $object->setDateFin($dateFin);

        switch ($object) {
            case $object instanceof StructureResponsable :
            case $object instanceof StructureGestionnaire :
                $object->setAgent($responsable);
                break;
            default : throw new RuntimeException("ResponsabiliteHydrator::hydrate : Type d'objet inconnu [" . get_class($object) . "]");
        }

        return $object;
    }


}
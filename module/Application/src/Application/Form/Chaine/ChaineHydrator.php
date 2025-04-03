<?php

namespace Application\Form\Chaine;

use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Laminas\Hydrator\HydratorInterface;
use RuntimeException;

class ChaineHydrator implements HydratorInterface
{
    use AgentServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var  AgentSuperieur|AgentAutorite $object */
        switch ($object) {
            case $object instanceof AgentSuperieur :
                $responsable = $object->getSuperieur();
                break;
            case $object instanceof AgentAutorite :
                $responsable = $object->getAutorite();
                break;
            default : throw new RuntimeException("ChaineHydrator::extract : Type d'objet inconnu [" . get_class($object) . "]");
        }

        $data = [
            'agent' => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()] :null,
            'responsable' => ($responsable)?['id' => $responsable->getId(), 'label' => $responsable->getDenomination()] :null,
            'date_debut' => ($object->getDateDebut())?$object->getDateDebut()->format('Y-m-d'):null,
            'date_fin' => ($object->getDateFin())?$object->getDateFin()->format('Y-m-d'):null,
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $agent = (isset($data['agent']['id']) AND trim($data['agent']['id']) !== '')?$this->getAgentService()->getAgent($data['agent']['id']):null;
        $responsable = (isset($data['responsable']['id']) AND trim($data['responsable']['id']) !== '')?$this->getAgentService()->getAgent($data['responsable']['id']):null;
        $dateDebut = (isset($data['date_debut']) AND $data['date_debut'] !== '') ? DateTime::createFromFormat('Y-m-d H:i:s', $data['date_debut']. " 08:00:00") :null;
        $dateFin = (isset($data['date_fin']) AND $data['date_fin'] !== '') ? DateTime::createFromFormat('Y-m-d H:i:s', $data['date_fin']. " 18:00:00") :null;

        $object->setAgent($agent);
        $object->setDateDebut($dateDebut);
        $object->setDateFin($dateFin);

        switch ($object) {
            case $object instanceof AgentSuperieur : $object->setSuperieur($responsable); break;
            case $object instanceof AgentAutorite : $object->setAutorite($responsable); break;
            default : throw new RuntimeException("ChaineHydrator::hydrate : Type d'objet inconnu [" . get_class($object) . "]");
        }

        return $object;
    }


}
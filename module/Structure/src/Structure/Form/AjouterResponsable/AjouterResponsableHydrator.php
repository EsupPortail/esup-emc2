<?php

namespace Structure\Form\AjouterResponsable;

use Application\Form\HasPeriode\HasPeriodeFieldset;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Structure\Entity\Db\StructureResponsable;
use Laminas\Hydrator\HydratorInterface;

class AjouterResponsableHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;

    /**
     * @param StructureResponsable $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'responsable'             => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
            'HasPeriode'        => [
                'date_debut' => ($object->getDateDebut())?$object->getDateDebut()->format(HasPeriodeFieldset::format):null,
                'date_fin'   => ($object->getDateFin())?$object->getDateFin()->format(HasPeriodeFieldset::format):null,
            ],
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param StructureResponsable $object
     * @return StructureResponsable
     */
    public function hydrate(array $data, $object)
    {
        $agent = (isset($data['responsable']) AND isset($data['responsable']['id']) AND trim($data['responsable']['id']) !== '')?$this->getAgentService()->getAgent($data['responsable']['id'], true):null;
        $dataDebut = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_debut']) AND trim($data['HasPeriode']['date_debut']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_debut']):null;
        $dateFin = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_fin']) AND trim($data['HasPeriode']['date_fin']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_fin']):null;

        $object->setAgent($agent);
        $object->setDateDebut($dataDebut);
        $object->setDateFin($dateFin);

        return $object;
    }


}
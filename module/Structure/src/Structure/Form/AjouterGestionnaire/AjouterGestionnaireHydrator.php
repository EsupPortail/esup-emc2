<?php

namespace Structure\Form\AjouterGestionnaire;

use Application\Form\HasPeriode\HasPeriodeFieldset;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Structure\Entity\Db\StructureGestionnaire;
use Zend\Hydrator\HydratorInterface;

class AjouterGestionnaireHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;

    /**
     * @param StructureGestionnaire $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'gestionnaire'             => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
            'HasPeriode'        => [
                'date_debut' => ($object->getDateDebut())?$object->getDateDebut()->format(HasPeriodeFieldset::format):null,
                'date_fin'   => ($object->getDateFin())?$object->getDateFin()->format(HasPeriodeFieldset::format):null,
            ],
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param StructureGestionnaire $object
     * @return StructureGestionnaire
     */
    public function hydrate(array $data, $object)
    {
        $agent = (isset($data['gestionnaire']) AND isset($data['gestionnaire']['id']) AND trim($data['gestionnaire']['id']) !== '')?$this->getAgentService()->getAgent($data['gestionnaire']['id']):null;
        $dataDebut = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_debut']) AND trim($data['HasPeriode']['date_debut']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_debut']):null;
        $dateFin = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_fin']) AND trim($data['HasPeriode']['date_fin']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_fin']):null;

        $object->setAgent($agent);
        $object->setDateDebut($dataDebut);
        $object->setDateFin($dateFin);

        return $object;
    }


}
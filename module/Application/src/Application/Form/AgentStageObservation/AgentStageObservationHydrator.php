<?php

namespace Application\Form\AgentStageObservation;

use Application\Entity\Db\AgentStageObservation;
use Application\Form\HasPeriode\HasPeriodeFieldset;
use DateTime;
use Laminas\Hydrator\HydratorInterface;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;

class AgentStageObservationHydrator implements HydratorInterface {
    use EtatTypeServiceAwareTrait;
    use StructureServiceAwareTrait;
    use MetierServiceAwareTrait;

    /**
     * @param AgentStageObservation $object
     * @return array
     */
    public function extract($object) : array
    {
        $data = [
            'structure'         => ($object->getStructure())?->getId(),
            'metier'            => ($object->getMetier())?->getId(),
            'HasPeriode'        => [
                'date_debut' => ($object->getDateDebut())?$object->getDateDebut()->format(HasPeriodeFieldset::format):null,
                'date_fin'   => ($object->getDateFin())?$object->getDateFin()->format(HasPeriodeFieldset::format):null,
            ],
            'etat'              => [
                'etat' => ($object->getEtat())?$object->getEtat()->getId():null,
            ],
            'complement'        => $object->getComplement(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param AgentStageObservation $object
     * @return AgentStageObservation
     */
    public function hydrate(array $data, $object): object
    {
        $structure = (isset($data['structure']) AND trim($data['structure']) !== '')?$this->getStructureService()->getStructure($data['structure']):null;
        $metier = (isset($data['metier']) AND trim($data['metier']) !== '')?$this->getMetierService()->getMetier($data['metier']):null;
        $dataDebut = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_debut']) AND trim($data['HasPeriode']['date_debut']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_debut']):null;
        $dateFin = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_fin']) AND trim($data['HasPeriode']['date_fin']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_fin']):null;
        $etat = (isset($data['etat']) AND isset($data['etat']['etat']))?$this->getEtatTypeService()->getEtatType($data['etat']['etat']):null;
        $complement = (isset($data['complement']) AND trim($data['complement']) !== '')?trim($data['complement']):null;

        $object->setStructure($structure);
        $object->setMetier($metier);
        $object->setDateDebut($dataDebut);
        $object->setDateFin($dateFin);
        $object->setEtat($etat);
        $object->setComplement($complement);

        return $object;
    }


}
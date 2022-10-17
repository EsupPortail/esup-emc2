<?php

namespace Application\Form\AgentTutorat;

use Application\Entity\Db\AgentTutorat;
use Application\Form\HasPeriode\HasPeriodeFieldset;
use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use Metier\Service\Metier\MetierServiceAwareTrait;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class AgentTutoratHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use MetierServiceAwareTrait;
    use EtatServiceAwareTrait;

    /**
     * @param AgentTutorat $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'cible'             => ($object->getCible())?['id' => $object->getCible()->getId(), 'label' => $object->getCible()->getDenomination()]:null,
            'metier'            => ($object->getMetier())?($object->getMetier())->getId():null,
            'HasPeriode'        => [
                'date_debut' => ($object->getDateDebut())?$object->getDateDebut()->format(HasPeriodeFieldset::format):null,
                'date_fin'   => ($object->getDateFin())?$object->getDateFin()->format(HasPeriodeFieldset::format):null,
            ],
            'etat'              => [
                'etat' => ($object->getEtat())?$object->getEtat()->getId():null,
            ],
            'complement'        => $object->getComplement(),
            'formation'        => $object->getFormation(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param AgentTutorat $object
     * @return AgentTutorat
     */
    public function hydrate(array $data, $object)
    {
        $cible = (isset($data['cible']) AND isset($data['cible']['id']) AND trim($data['cible']['id']) !== '')?$this->getAgentService()->getAgent($data['cible']['id']):null;
        $metier = (isset($data['metier']) AND trim($data['metier']) !== '')?$this->getMetierService()->getMetier($data['metier']):null;
        $dataDebut = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_debut']) AND trim($data['HasPeriode']['date_debut']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_debut']):null;
        $dateFin = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_fin']) AND trim($data['HasPeriode']['date_fin']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_fin']):null;
        $etat = (isset($data['etat']) AND isset($data['etat']['etat']))?$this->getEtatService()->getEtat($data['etat']['etat']):null;
        $complement = (isset($data['complement']) AND trim($data['complement']) !== '')?trim($data['complement']):null;
        $formation = (isset($data['formation']) AND trim($data['formation']) !== '')?($data['formation']):null;

        $object->setCible($cible);
        $object->setMetier($metier);
        $object->setDateDebut($dataDebut);
        $object->setDateFin($dateFin);
        $object->setEtat($etat);
        $object->setComplement($complement);
        $object->setFormation($formation);

        return $object;
    }


}
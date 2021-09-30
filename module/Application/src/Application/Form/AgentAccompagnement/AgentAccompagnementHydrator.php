<?php

namespace Application\Form\AgentAccompagnement;

use Application\Entity\Db\AgentAccompagnement;
use Application\Form\HasPeriode\HasPeriodeFieldset;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\Corps\CorpsServiceAwareTrait;
use Application\Service\Correspondance\CorrespondanceServiceAwareTrait;
use DateTime;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class AgentAccompagnementHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use CorpsServiceAwareTrait;
    use EtatServiceAwareTrait;

    /**
     * @param AgentAccompagnement $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'cible'             => ($object->getCible())?['id' => $object->getCible()->getId(), 'label' => $object->getCible()->getDenomination()]:null,
            'bap'               => ($object->getBap())?($object->getBap())->getId():null,
            'corps'             => ($object->getCorps())?($object->getCorps())->getId():null,
            'HasPeriode'        => [
                'date_debut' => ($object->getDateDebut())?$object->getDateDebut()->format('d/m/Y'):null,
                'date_fin'   => ($object->getDateFin())?$object->getDateFin()->format('d/m/Y'):null,
            ],
            'etat'              => [
                'etat' => ($object->getEtat())?$object->getEtat()->getId():null,
            ],
            'complement'        => $object->getComplement(),
            'resutlat'          => $object->getResultat(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param AgentAccompagnement $object
     * @return AgentAccompagnement
     */
    public function hydrate(array $data, $object)
    {
        $cible = (isset($data['cible']) AND isset($data['cible']['id']) AND trim($data['cible']['id']) !== '')?$this->getAgentService()->getAgent($data['cible']['id']):null;
        $bap = (isset($data['bap']) AND trim($data['bap']) !== '')?$this->getCorrespondanceService()->getCorrespondance($data['bap']):null;
        $corps = (isset($data['corps']) AND trim($data['corps']) !== '')?$this->getCorpsService()->getCorp($data['corps']):null;
        $dataDebut = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_debut']) AND trim($data['HasPeriode']['date_debut']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_debut']):null;
        $dateFin = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_fin']) AND trim($data['HasPeriode']['date_fin']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_fin']):null;
        $etat = (isset($data['etat']) AND isset($data['etat']['etat']))?$this->getEtatService()->getEtat($data['etat']['etat']):null;
        $complement = (isset($data['complement']) AND trim($data['complement']) !== '')?trim($data['complement']):null;
        $resultat = (isset($data['resultat']) AND trim($data['resultat']) !== '')?($data['resultat']):null;

        $object->setCible($cible);
        $object->setBap($bap);
        $object->setCorps($corps);
        $object->setDateDebut($dataDebut);
        $object->setDateFin($dateFin);
        $object->setEtat($etat);
        $object->setComplement($complement);
        $object->setResultat($resultat);

        return $object;
    }


}
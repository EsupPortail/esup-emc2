<?php

namespace Application\Form\AgentAccompagnement;

use Application\Entity\Db\AgentAccompagnement;
use Application\Form\HasPeriode\HasPeriodeFieldset;
use Application\Service\Agent\AgentServiceAwareTrait;
use Carriere\Service\Corps\CorpsServiceAwareTrait;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use DateTime;
use Laminas\Hydrator\HydratorInterface;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;

class AgentAccompagnementHydrator implements HydratorInterface
{
    use AgentServiceAwareTrait;
    use CorpsServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use EtatTypeServiceAwareTrait;

    /**
     * @param AgentAccompagnement $object
     * @return array
     */
    public function extract(object $object): array
    {
        $data = [
            'cible' => ($object->getCible()) ? ['id' => $object->getCible()->getId(), 'label' => $object->getCible()->getDenomination()] : null,
            'bap' => ($object->getBap()) ? ($object->getBap())->getId() : null,
            'corps' => ($object->getCorps()) ? ($object->getCorps())->getId() : null,
            'HasPeriode' => [
                'date_debut' => ($object->getDateDebut()) ? $object->getDateDebut()->format(HasPeriodeFieldset::format) : null,
                'date_fin' => ($object->getDateFin()) ? $object->getDateFin()->format(HasPeriodeFieldset::format) : null,
            ],
            'etat' => [
                'etat' => ($object->getEtat()) ? $object->getEtat()->getId() : null,
            ],
            'complement' => $object->getComplement(),
            'resutlat' => $object->getResultat(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param AgentAccompagnement $object
     * @return AgentAccompagnement
     */
    public function hydrate(array $data, object $object): object
    {
        $cible = (isset($data['cible']) and isset($data['cible']['id']) and trim($data['cible']['id']) !== '') ? $this->getAgentService()->getAgent($data['cible']['id']) : null;
        $bap = (isset($data['bap']) and trim($data['bap']) !== '') ? $this->getCorrespondanceService()->getCorrespondance($data['bap']) : null;
        $corps = (isset($data['corps']) and trim($data['corps']) !== '') ? $this->getCorpsService()->getCorp($data['corps']) : null;
        $dataDebut = (isset($data['HasPeriode']) and isset($data['HasPeriode']['date_debut']) and trim($data['HasPeriode']['date_debut']) !== '') ? DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_debut']) : null;
        $dateFin = (isset($data['HasPeriode']) and isset($data['HasPeriode']['date_fin']) and trim($data['HasPeriode']['date_fin']) !== '') ? DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_fin']) : null;
        $etat = (isset($data['etat']) and isset($data['etat']['etat'])) ? $this->getEtatTypeService()->getEtatTypeByCode($data['etat']['etat']) : null;
        $complement = (isset($data['complement']) and trim($data['complement']) !== '') ? trim($data['complement']) : null;
        $resultat = (isset($data['resultat']) and trim($data['resultat']) !== '') ? ($data['resultat']) : null;

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
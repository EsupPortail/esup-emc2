<?php

namespace Application\Service\Evenement;

use Application\Provider\EvenementProvider;
use DateTime;
use Exception;
use UnicaenDbImport\Service\Traits\SynchroServiceAwareTrait;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;

class SynchroOctopusService extends EvenementService {
    use SynchroServiceAwareTrait;

    /**
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(DateTime $dateTraitement = null) : Evenement
    {
        $type = $this->getTypeService()->findByCode(EvenementProvider::SYNCHRO_OCTOPUS);
        $description = "Synchonisation avec octopus ".(new DateTime())->format('d/m/Y');
        $evenement = $this->createEvent($description, $description, null, $type, null, $dateTraitement);
        $this->ajouter($evenement);
        return $evenement;
    }

    /**
     * @param Evenement $evenement
     * @return string
     */
    public function traiter(Evenement $evenement) : string
    {
        try {
            $log = "";
            $synchros = $this->getSynchroService()->getSynchros();
            foreach ($synchros as $synchro) {
                $log .= "### " . $synchro->getName() . " ###<br/>\n";
                $log .= $this->getSynchroService()->runSynchro($synchro);
                $log .= "<br/>\n";
            }
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            return Etat::ECHEC;
        }
        $evenement->setLog($log);
        $this->update($evenement);
        return Etat::SUCCES;
    }


}
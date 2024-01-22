<?php

namespace Application\Service\Evenement;

use Application\Provider\EvenementProvider;
use DateTime;
use Exception;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Service\Evenement\EvenementService;
use UnicaenSynchro\Service\Synchronisation\SynchronisationServiceAwareTrait;

class SynchroOctopusService extends EvenementService {
    use SynchronisationServiceAwareTrait;

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
        $log = "";
        try {
            $jobs = $this->getSynchronisationService()->getConfigs();
            foreach ($jobs as $name => $job) {
                $texte =  $this->getSynchronisationService()->synchronise($name);
                $log .= $texte;
                echo $texte;
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
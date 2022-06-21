<?php

namespace Application\Event\RgpdRenderer;

use Application\Provider\EvenementProvider;
use DateInterval;
use DateTime;
use Exception;
use UnicaenEvenement\Entity\Db\Etat;
use UnicaenEvenement\Entity\Db\Evenement;
use UnicaenEvenement\Entity\Db\Type;
use UnicaenEvenement\Service\Evenement\EvenementService;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;

class RgpdRendererEvenement extends EvenementService {
    use RenduServiceAwareTrait;

    /**
     * @param DateTime|null $dateTraitement
     * @return Evenement
     */
    public function creer(DateTime $dateTraitement = null) : Evenement
    {
        /** @var Type $type */
        $type = $this->getTypeService()->findByCode(EvenementProvider::RGPD_UNICAEN_RENDERER);
        $description = $type->getLibelle(). ' ' .(new DateTime())->format('d/m/Y');
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
            //todo as param 'P3M'
            $date = (new DateTime())->sub( new DateInterval('P3M'));
            $rendus = $this->getRenduService()->getRenduOlderThan($date);
            $nb = 0;
            foreach ($rendus as $rendu) {
                $this->getRenduService()->delete($rendu);
                $nb++;
            }
        } catch(Exception $e) {
            $evenement->setLog($e->getMessage());
            $this->update($evenement);
            return Etat::ECHEC;
        }
        $evenement->setLog($nb . " rendus ont été détruits.");
        $this->update($evenement);
        return Etat::SUCCES;
    }

}
<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use Application\Entity\HasAgentInterface;
use DateInterval;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Exception;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use RuntimeException;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FormationInstanceInscrit implements HistoriqueAwareInterface, HasAgentInterface, HasEtatInterface, HasSourceInterface, ResourceInterface
{
    use HasEtatTrait;
    use HasSourceTrait;
    use HistoriqueAwareTrait;

    public function getResourceId() : string
    {
        return 'Inscrit';
    }

    const PRINCIPALE = 'principale';
    const COMPLEMENTAIRE = 'complementaire';

    private ?int $id = null;
    private ?FormationInstance $instance = null;
    private ?Agent $agent = null;
    private ?string $liste = null;
    private Collection $presences;
    private ?FormationInstanceFrais $frais = null;
    private ?string $complement = null;
    private ?DateTime $validationEnquete = null;
    private Collection $reponsesEnquete;
    private ?string $justificationAgent = null;
    private ?string $justificationResponsable = null;
    private ?string $justificationRefus = null;

    public function __construct()
    {
        $this->presences = new ArrayCollection();
        $this->reponsesEnquete = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getInstance() : ?FormationInstance
    {
        return $this->instance;
    }

    public function setInstance(?FormationInstance $instance): void
    {
        $this->instance = $instance;
    }

    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent) : void
    {
        $this->agent = $agent;
    }

    public function getListe() : ?string
    {
        return $this->liste;
    }

    public function setListe(?string $liste) : void
    {
        $this->liste = $liste;
    }

    public function getFrais(): ?FormationInstanceFrais
    {
        return $this->frais;
    }

    public function setFrais(?FormationInstanceFrais $frais): void
    {
        $this->frais = $frais;
    }


    /** @return EnqueteReponse[] */
    public function getReponsesEnquete(): array
    {
        $responses = $this->reponsesEnquete->toArray();
        $responses = array_filter($responses, function (EnqueteReponse $a) { return $a->estNonHistorise(); });
        return $responses;
    }

    /** JUSTIFICATIONS  *************************************************************************************/

    public function getJustificationAgent(): ?string
    {
        return $this->justificationAgent;
    }

    public function setJustificationAgent(?string $justificationAgent): void
    {
        $this->justificationAgent = $justificationAgent;
    }

    public function getJustificationResponsable(): ?string
    {
        return $this->justificationResponsable;
    }

    public function setJustificationResponsable(?string $justificationResponsable): void
    {
        $this->justificationResponsable = $justificationResponsable;
    }

    public function getJustificationRefus(): ?string
    {
        return $this->justificationRefus;
    }

    public function setJustificationRefus(?string $justificationRefus): void
    {
        $this->justificationRefus = $justificationRefus;
    }

    public function getValidationEnquete(): ?DateTime
    {
        return $this->validationEnquete;
    }

    public function setValidationEnquete(?DateTime $validationEnquete): void
    {
        $this->validationEnquete = $validationEnquete;
    }

    /** PRESENCES *****************************************************************************************************/

    public function getPresences() : array
    {
        return $this->presences->toArray();
    }

    public function wasPresent(Seance $journee) : bool
    {
        /** @var Presence $presence */
        foreach ($this->presences as $presence) {
            if ($presence->getJournee() === $journee) return $presence->isPresent();
        }
        return false;
    }

    public function getDureePresence() : string
    {
        $sum = DateTime::createFromFormat('d/m/Y H:i', '01/01/1970 00:00');
        /** @var Presence[] $presences */
        $presences = array_filter($this->presences->toArray(), function (Presence $a) {
            return $a->estNonHistorise() and $a->isPresent();
        });
        foreach ($presences as $presence) {
            $journee = $presence->getJournee();
            if ($journee->getType() === Seance::TYPE_SEANCE) {
                $debut = DateTime::createFromFormat('d/m/Y H:i', $journee->getJour()->format('d/m/Y') . " " . $journee->getDebut());
                $fin = DateTime::createFromFormat('d/m/Y H:i', $journee->getJour()->format('d/m/Y') . " " . $journee->getFin());
                $duree = $debut->diff($fin);
                $sum->add($duree);
            }
            if ($journee->getType() === Seance::TYPE_VOLUME) {
                $volume = $journee->getVolume();
                try {
                    $temp = new DateInterval('PT' . $volume . 'H');
                } catch (Exception $e) {
                    throw new RuntimeException("Unproblème est survenu lors de la création de l'intervale avec [PT".$volume."H]");
                }
                $sum->add($temp);
            }
        }

        $result = $sum->diff(DateTime::createFromFormat('d/m/Y H:i', '01/01/1970 00:00'));
        $heures = ($result->d * 24 + $result->h);
        $minutes = ($result->i);
        $text = $heures . " heures " . (($minutes !== 0) ? ($minutes . " minutes") : "");
        return $text;
    }


}
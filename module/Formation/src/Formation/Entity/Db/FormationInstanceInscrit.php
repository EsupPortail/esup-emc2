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

    /**
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return FormationInstance|null
     */
    public function getInstance() : ?FormationInstance
    {
        return $this->instance;
    }

    /**
     * @param FormationInstance|null $instance
     * @return FormationInstanceInscrit
     */
    public function setInstance(?FormationInstance $instance): FormationInstanceInscrit
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getAgent() : ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent|null $agent
     * @return FormationInstanceInscrit
     */
    public function setAgent(?Agent $agent) : FormationInstanceInscrit
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getListe() : ?string
    {
        return $this->liste;
    }

    /**
     * @param string|null $liste
     * @return FormationInstanceInscrit
     */
    public function setListe(?string $liste) : FormationInstanceInscrit
    {
        $this->liste = $liste;
        return $this;
    }


    /**
     * @return FormationInstanceFrais|null
     */
    public function getFrais(): ?FormationInstanceFrais
    {
        return $this->frais;
    }

    /**
     * @param FormationInstanceFrais|null $frais
     * @return FormationInstanceInscrit
     */
    public function setFrais(?FormationInstanceFrais $frais): FormationInstanceInscrit
    {
        $this->frais = $frais;
        return $this;
    }


    /**
     * @return EnqueteReponse[]
     */
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
<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use Application\Entity\HasAgentInterface;
use UnicaenAutoform\Entity\Db\FormulaireInstance;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenEtat\Entity\Db\HasEtatInterface;
use UnicaenEtat\Entity\Db\HasEtatTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;

class FormationInstanceInscrit implements HistoriqueAwareInterface, HasAgentInterface, HasEtatInterface, HasSourceInterface, ResourceInterface
{
    use HasEtatTrait;
    use HasSourceTrait;
    use HistoriqueAwareTrait;

    const ETAT_DEMANDE_INSCRIPTION      = 'DEMANDE_INSCRIPTION';
    const ETAT_VALIDATION_RESPONSABLE   = 'VALIDATION_RESPONSABLE';
    const ETAT_VALIDATION_INSCRIPTION   = 'VALIDATION_INSCRIPTION';
    const ETAT_REFUS_INSCRIPTION        = 'REFUS_INSCRIPTION';

    public function getResourceId()
    {
        return 'Inscrit';
    }

    const PRINCIPALE = 'principale';
    const COMPLEMENTAIRE = 'complementaire';

    /** @var integer */
    private $id;
    /** @var FormationInstance */
    private $instance;
    /** @var Agent */
    private $agent;
    /** @var string */
    private $liste;
    /** @var ArrayCollection (FormationInstancePresence) */
    private $presences;
    /** @var FormationInstanceFrais */
    private $frais;
    /** @var FormulaireInstance */
    private $questionnaire;
    /** @var string|null */
    private $complement;

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
     * @return FormulaireInstance|null
     */
    public function getQuestionnaire(): ?FormulaireInstance
    {
        return $this->questionnaire;
    }

    /**
     * @param FormulaireInstance $questionnaire
     * @return FormationInstanceInscrit
     */
    public function setQuestionnaire(FormulaireInstance $questionnaire): FormationInstanceInscrit
    {
        $this->questionnaire = $questionnaire;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplement(): ?string
    {
        return $this->complement;
    }

    /**
     * @param string|null $complement
     * @return FormationInstanceInscrit
     */
    public function setComplement(?string $complement): FormationInstanceInscrit
    {
        $this->complement = $complement;
        return $this;
    }

    public function getPresences() : array
    {
        return $this->presences->toArray();
    }

    public function wasPresent(FormationInstanceJournee $journee)
    {
        /** @var FormationInstancePresence $presence */
        foreach ($this->presences as $presence) {
            if ($presence->getJournee() === $journee) return $presence->isPresent();
        }
        return false;
    }

    public function getDureePresence() : string
    {
        $sum = DateTime::createFromFormat('d/m/Y H:i', '01/01/1970 00:00');
        /** @var FormationInstancePresence[] $presences */
        $presences = array_filter($this->presences->toArray(), function (FormationInstancePresence $a) {
            return $a->estNonHistorise() and $a->isPresent();
        });
        foreach ($presences as $presence) {
            $journee = $presence->getJournee();
            $debut = DateTime::createFromFormat('d/m/Y H:i', $journee->getJour()->format('d/m/Y') . " " . $journee->getDebut());
            $fin = DateTime::createFromFormat('d/m/Y H:i', $journee->getJour()->format('d/m/Y') . " " . $journee->getFin());
            $duree = $fin->diff($debut);
            $sum->add($duree);
        }

        $result = $sum->diff(DateTime::createFromFormat('d/m/Y H:i', '01/01/1970 00:00'));
        $heures = ($result->d * 24 + $result->h);
        $minutes = ($result->i);
        $text = $heures . " heures " . (($minutes !== 0) ? ($minutes . " minutes") : "");
        return $text;
    }

}
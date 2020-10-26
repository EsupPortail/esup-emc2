<?php

namespace Application\Entity\Db;

use Application\Entity\HasAgentInterface;
use Autoform\Entity\Db\FormulaireInstance;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Formation\Entity\Db\FormationInstanceFrais;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use Zend\Permissions\Acl\Resource\ResourceInterface;

class FormationInstanceInscrit implements HistoriqueAwareInterface, HasAgentInterface, ResourceInterface {
    use HistoriqueAwareTrait;

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

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FormationInstance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstanceInscrit
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return FormationInstanceInscrit
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return string
     */
    public function getListe()
    {
        return $this->liste;
    }

    /**
     * @param string $liste
     * @return FormationInstanceInscrit
     */
    public function setListe($liste)
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


    public function getPresences()
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

    public function getDureePresence()
    {
        $sum = DateTime::createFromFormat('d/m/Y H:i','01/01/1970 00:00');
        /** @var FormationInstancePresence[] $presences */
        $presences = array_filter($this->presences->toArray(), function (FormationInstancePresence $a) { return $a->estNonHistorise() AND $a->isPresent(); });
        foreach ($presences as $presence) {
            $journee = $presence->getJournee();
            $debut = DateTime::createFromFormat('d/m/Y H:i',$journee->getJour()." ".$journee->getDebut());
            $fin = DateTime::createFromFormat('d/m/Y H:i',$journee->getJour()." ".$journee->getFin());
            $duree = $fin->diff($debut);
            $sum->add($duree);
        }

        $result = $sum->diff(DateTime::createFromFormat('d/m/Y H:i','01/01/1970 00:00'));
        $heures = ($result->d * 24 + $result->h);
        $minutes = ($result->i);
        $text = $heures . " heures " . (($minutes !== 0)?($minutes." minutes"):"");
        return $text;
    }
}
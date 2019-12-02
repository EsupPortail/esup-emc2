<?php

namespace Utilisateur\Entity;

use DateTime;
use UnicaenApp\Entity\UserInterface;
use UnicaenApp\Exception\RuntimeException;
use Utilisateur\Service\User\UserService;
use Utilisateur\Service\User\UserServiceAwareTrait;

/**
 * Code commun aux entités possédant une gestion d'historique.
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
trait HistoriqueAwareTrait
{
    /**
     * @var DateTime
     */
    protected $histoCreation;

    /**
     * @var DateTime
     */
    protected $histoModification;

    /**
     * @var DateTime
     */
    protected $histoDestruction;

    /**
     * @var UserInterface
     */
    protected $histoCreateur;

    /**
     * @var UserInterface
     */
    protected $histoModificateur;

    /**
     * @var UserInterface
     */
    protected $histoDestructeur;

    /**
     * Set histoCreation
     *
     * @param DateTime $histoCreation
     *
     * @return self
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }

    /**
     * Get histoCreation
     *
     * @return DateTime
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }

    /**
     * Set histoDestruction
     *
     * @param DateTime $histoDestruction
     *
     * @return self
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }

    /**
     * Get histoDestruction
     *
     * @return DateTime
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
    }

    /**
     * Set histoModification
     *
     * @param DateTime $histoModification
     *
     * @return self
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }

    /**
     * Get histoModification
     *
     * @return DateTime
     */
    public function getHistoModification()
    {
        return $this->histoModification;
    }

    /**
     * Set histoModificateur
     *
     * @param UserInterface $histoModificateur
     *
     * @return self
     */
    public function setHistoModificateur(UserInterface $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return UserInterface
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoDestructeur
     *
     * @param UserInterface $histoDestructeur
     *
     * @return self
     */
    public function setHistoDestructeur(UserInterface $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return UserInterface
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set histoCreateur
     *
     * @param UserInterface $histoCreateur
     *
     * @return self
     */
    public function setHistoCreateur(UserInterface $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return UserInterface
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

    /**
     * Marque cet enregistrement comme historisé.
     *
     * @see HistoriqueAwareInterface
     *
     * @param UserInterface|null $destructeur     Auteur de la suppression ; si null, peut être renseigné
     *                                            automatiquement (cf. HistoriqueListener) si la classe implémente
     *                                            HistoriqueAwareInterface
     * @param DateTime|null      $dateDestruction Date éventuelle de la suppression
     * @return $this
     */
    public function historiser(UserInterface $destructeur = null, DateTime $dateDestruction = null)
    {
        if ($destructeur) {
            $this->setHistoDestructeur($destructeur);
        }

        if (empty($dateDestruction)) {
            try {
                $dateDestruction = new DateTime();
            } catch (\Exception $e) {
                throw new RuntimeException("Impossible d'instancier un DateTime!", null, $e);
            }
        }

        $this->setHistoDestruction($dateDestruction);

        return $this;
    }

    /**
     * Annule l'historisation de cet enregistrement.
     *
     * @see HistoriqueAwareInterface
     * @return $this
     */
    public function dehistoriser()
    {
        $this->setHistoDestructeur(null);
        $this->setHistoDestruction(null);

        return $this;
    }

    /**
     * Retourne true si l'entité est historisée.
     *
     * @param DateTime|null $dateObs Date d'observation éventuelle
     * @return bool
     */
    public function estHistorise(DateTime $dateObs = null)
    {
        return ! $this->estNonHistorise($dateObs);
    }

    /**
     * Retourne true si l'entité n'est *pas* historisée.
     *
     * @param DateTime|null $dateObs Date d'observation éventuelle
     * @return bool
     */
    public function estNonHistorise(DateTime $dateObs = null)
    {
        if (empty($dateObs)) {
            try {
                $dateObs = new DateTime();
            } catch (\Exception $e) {
                throw new RuntimeException("Impossible d'instancier un DateTime!", null, $e);
            }
        }

        $dObs = $dateObs->format('Y-m-d');
        $dDeb = $this->getHistoCreation() ? $this->getHistoCreation()->format('Y-m-d') : null;
        $dFin = $this->getHistoDestruction() ? $this->getHistoDestruction()->format('Y-m-d') : null;

        if ($dDeb && !($dDeb <= $dObs)) return false;
        if ($dFin && !($dObs < $dFin)) return false;

        return true;
    }

    /**
     * @param UserService $userService
     * @return $this
     */
    public function updateCreation(UserService $userService = null)
    {
        $date = null;
        $user = null;
        try {
            $date = new DateTime();
        } catch (\Exception $e) {
            throw new RuntimeException("Impossible d'instancier un DateTime!", null, $e);
        }

        if ($userService !== null) $user = $userService->getConnectedUser();

        $this->setHistoCreation($date);
        $this->setHistoCreateur($user);
        $this->setHistoModification($date);
        $this->setHistoModificateur($user);

        return $this;
    }

    /**
     * @param UserService $userService
     * @return $this
     */
    public function updateModification(UserService $userService = null)
    {
        $date = null;
        $user = null;
        try {
            $date = new DateTime();
        } catch (\Exception $e) {
            throw new RuntimeException("Impossible d'instancier un DateTime!", null, $e);
        }

        if ($userService !== null) $user = $userService->getConnectedUser();

        $this->setHistoModification($date);
        $this->setHistoModificateur($user);

        return $this;
    }

    /**
     * @param UserService $userService
     * @return $this
     */
    public function updateDestructeur(UserService $userService = null)
    {
        $date = null;
        $user = null;
        try {
            $date = new DateTime();
        } catch (\Exception $e) {
            throw new RuntimeException("Impossible d'instancier un DateTime!", null, $e);
        }

        if ($userService !== null) $user = $userService->getConnectedUser();

        $this->setHistoDestruction($date);
        $this->setHistoDestructeur($user);

        return $this;
    }
}
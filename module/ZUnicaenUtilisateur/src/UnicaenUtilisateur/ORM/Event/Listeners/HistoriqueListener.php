<?php

namespace UnicaenUtilisateur\ORM\Event\Listeners;

use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Exception;
use RuntimeException;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\AbstractUser;
use Zend\Authentication\AuthenticationService;

/**
 * Listener Doctrine.
 *
 * Renseigne si besoin l'heure et l'auteur de la création/modification
 * de toute entité dont la classe implémente HistoriqueAwareInterface.
 *
 * Déclenchement : avant que l'enregistrement ne soit persisté (création) ou mis à jour (update).
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 * @see HistoriqueAwareInterface
 */
class HistoriqueListener implements EventSubscriber
{
    /**
     * @var AuthenticationService
     */
    private $authenticationService;

    /**
     * @var mixed
     */
    protected $identity;

    /**
     * @param AuthenticationService $authenticationService
     */
    public function setAuthenticationService(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws RuntimeException Aucun utilisateur disponible pour en faire l'auteur de la création/modification
     */
    protected function updateHistorique(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        // l'entité doit implémenter l'interface requise
        if (! $entity instanceof HistoriqueAwareInterface) {
            return;
        }

        $now = null;
        try {
            $now = new DateTime();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème est survenu lors de la récupération de la date.");
        }

        if (null === $entity->getHistoCreation()) {
            $entity->setHistoCreation($now);
        }

        // on tente d'abord d'obtenir l'utilisateur connecté pour en faire l'auteur de la création/modification.
        $user = $this->getAuthenticatedUser();
        // si aucun utilisateur connecté n'est disponible, on utilise l'éventuel auteur existant
        if (null === $user) {
            $user = $entity->getHistoCreateur();
        }
        // si nous ne disposons d'aucun utilisateur, basta!
        if (null === $user) {
            throw new RuntimeException("Aucun utilisateur disponible pour en faire l'auteur de la création/modification.");
        }

        if (null === $entity->getHistoCreateur()) {
            $entity->setHistoCreateur($user);
        }

        $entity->setHistoModificateur($user);
        $entity->setHistoModification($now);
        /* ce bloc a été mis en commentaire car il est inutile: cf. 2 lignes précédentes !
        if (null === $entity->getHistoDestruction() && null === $entity->getHistoDestructeur()) {
            $entity
                ->setHistoModification($now)
                ->setHistoModificateur($user);
        }
        */

        if (null !== $entity->getHistoDestruction() && null === $entity->getHistoDestructeur()) {
            $entity->setHistoDestructeur($user);
        }
    }

    /**
     * Recherche l'utilisateur connecté pour l'utiliser comme auteur de la création/modification.
     *
     * @return AbstractUser
     */
    private function getAuthenticatedUser()
    {
        $user = null;

        if (($identity = $this->getIdentity())) {
            if (isset($identity['db']) && $identity['db'] instanceof AbstractUser) {
                /* @var $user AbstractUser */
                $user = $identity['db'];
            }
        }

        return $user;
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->updateHistorique($args);
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $this->updateHistorique($args);
    }

    /**
     * Injecte l'identité authentifiée courante.
     *
     * @param mixed $identity
     * @return self
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * Retourne l'identité authentifiée courante.
     *
     * @return mixed
     */
    public function getIdentity()
    {
        if (null === $this->identity) {
            $authenticationService = $this->authenticationService;
            if ($authenticationService->hasIdentity()) {
                $this->identity = $authenticationService->getIdentity();
            }
        }

        return $this->identity;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return [Events::prePersist, Events::preUpdate];
    }
}
<?php

namespace Formation\Service\InscriptionFrais;

use Doctrine\DBAL\Driver\Exception as DRV_Exception;
use Doctrine\DBAL\Exception as DBA_Exception;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Session;
use Formation\Entity\Db\InscriptionFrais;
use RuntimeException;

class InscriptionFraisService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES **********************************************************************************/

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function create(InscriptionFrais $frais) : InscriptionFrais
    {
        $this->getObjectManager()->persist($frais);
        $this->getObjectManager()->flush($frais);
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function update(InscriptionFrais $frais) : InscriptionFrais
    {
        $this->getObjectManager()->flush($frais);
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function historise(InscriptionFrais $frais) : InscriptionFrais
    {
        $frais->historiser();
        $this->getObjectManager()->flush($frais);
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function restore(InscriptionFrais $frais) : InscriptionFrais
    {
        $frais->dehistoriser();
        $this->getObjectManager()->flush($frais);
        return $frais;
    }

    /**
     * @param InscriptionFrais $frais
     * @return InscriptionFrais
     */
    public function delete(InscriptionFrais $frais) : InscriptionFrais
    {
        $this->getObjectManager()->remove($frais);
        $this->getObjectManager()->flush($frais);
        return $frais;
    }

    public function getFraisManquants(?Session $session): array
    {
        $sql = <<<EOS
select frais.id, coalesce(concat(agent.prenom, ' ', coalesce(agent.nom_usage, agent.nom_famille)), concat(stagiaire.prenom, ' ', stagiaire.nom)) AS personne , frais_hebergement, frais_repas, frais_transport
from formation_instance session
 left join formation_inscription inscription on session.id = inscription.session_id
 left join agent on inscription.agent_id = agent.c_individu
 left join formation_stagiaire_externe stagiaire on inscription.stagiaire_id = stagiaire.id
 left join formation_inscription_frais frais on inscription.id = frais.inscription_id
where true
  -- pas histo --
  AND    inscription.histo_destruction IS NULL
  -- selection --
  AND session.id=:session_id
  AND inscription.liste = 'principale'
  AND (frais_repas IS NULL OR frais_transport IS NULL OR frais_hebergement IS NULL)
EOS;

        try {
            $res = $this->getObjectManager()->getConnection()->executeQuery($sql, ['session_id' => $session->getId()]);
            try {
                $tmp = $res->fetchAllAssociative();
            } catch (DRV_Exception $e) {
                throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
            }
        } catch (DBA_Exception $e) {
            throw new RuntimeException("Un problème est survenue lors de la récupération des fonctions d'un groupe d'individus", 0, $e);
        }

        return $tmp;
    }
}
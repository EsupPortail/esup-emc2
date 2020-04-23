<?php

namespace Application\Service\CompetencesRetirees;

use Application\Entity\Db\Competence;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteCompetenceRetiree;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;

class CompetencesRetireesService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteCompetenceRetiree $competenceRetiree
     * @return FicheposteCompetenceRetiree
     */
    public function create(FicheposteCompetenceRetiree $competenceRetiree) {
        $this->createFromTrait($competenceRetiree);
        return $competenceRetiree;
    }

    /**
     * @param FicheposteCompetenceRetiree $competenceConservee
     * @return FicheposteCompetenceRetiree
     */
    public function update(FicheposteCompetenceRetiree $competenceConservee) {
        $this->updateFromTrait($competenceConservee);
        return $competenceConservee;
    }

    /**
     * @param FicheposteCompetenceRetiree $competenceConservee
     * @return FicheposteCompetenceRetiree
     */
    public function delete(FicheposteCompetenceRetiree $competenceConservee) {
        $this->deleteFromTrait($competenceConservee);
        return $competenceConservee;
    }

    /** ACCESSEUR *****************************************************************************************************/

    /**
     * @param FichePoste $ficheposte
     * @param Competence $competence
     * @return FicheposteCompetenceRetiree
     */
    public function getCompetenceRetiree(FichePoste $ficheposte, Competence $competence)
    {
        $qb = $this->getEntityManager()->getRepository(FicheposteCompetenceRetiree::class)->createQueryBuilder('retiree')
            ->andWhere('retiree.fichePoste = :ficheposte')
            ->andWhere('retiree.competence = :competence')
            ->setParameter('ficheposte', $ficheposte)
            ->setParameter('competence', $competence);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetencesRetirees ...",0,$e);
        }
        return $result;

    }

    /**
     * @param FichePoste $ficheposte
     * @param Competence $competence
     * @return FicheposteCompetenceRetiree
     */
    public function add(FichePoste $ficheposte, Competence $competence)
    {
        $result = $this->getCompetenceRetiree($ficheposte, $competence);

        if ($result === null) {
            $result = new FicheposteCompetenceRetiree();
            $result->setFichePoste($ficheposte);
            $result->setCompetence($competence);
            $this->create($result);
        }
        return $result;
    }

    /**
     * @param FichePoste $ficheposte
     * @param Competence $competence
     * @return FicheposteCompetenceRetiree
     */
    public function remove(FichePoste $ficheposte, Competence $competence)
    {
        $result = $this->getCompetenceRetiree($ficheposte, $competence);

        if ($result !== null) {
            $this->delete($result);
        }
        return $result;
    }

}
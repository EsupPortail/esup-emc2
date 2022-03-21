<?php

namespace Application\Service\CompetencesRetirees;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteCompetenceRetiree;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Element\Entity\Db\Competence;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class CompetencesRetireesService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteCompetenceRetiree $competenceRetiree
     * @return FicheposteCompetenceRetiree
     */
    public function create(FicheposteCompetenceRetiree $competenceRetiree) : FicheposteCompetenceRetiree
    {
        try {
            $this->getEntityManager()->persist($competenceRetiree);
            $this->getEntityManager()->flush($competenceRetiree);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competenceRetiree;
    }

    /**
     * @param FicheposteCompetenceRetiree $competenceConservee
     * @return FicheposteCompetenceRetiree
     */
    public function update(FicheposteCompetenceRetiree $competenceConservee) : FicheposteCompetenceRetiree
    {
        try {
            $this->getEntityManager()->flush($competenceConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competenceConservee;
    }

    /**
     * @param FicheposteCompetenceRetiree $competenceConservee
     * @return FicheposteCompetenceRetiree
     */
    public function delete(FicheposteCompetenceRetiree $competenceConservee) : FicheposteCompetenceRetiree
    {
        try {
            $this->getEntityManager()->remove($competenceConservee);
            $this->getEntityManager()->flush($competenceConservee);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $competenceConservee;
    }

    /** ACCESSEUR *****************************************************************************************************/

    /**
     * @param FichePoste $ficheposte
     * @param Competence $competence
     * @return FicheposteCompetenceRetiree
     */
    public function getCompetenceRetiree(FichePoste $ficheposte, Competence $competence) : ?FicheposteCompetenceRetiree
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
    public function add(FichePoste $ficheposte, Competence $competence) : FicheposteCompetenceRetiree
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
    public function remove(FichePoste $ficheposte, Competence $competence) : FicheposteCompetenceRetiree
    {
        $result = $this->getCompetenceRetiree($ficheposte, $competence);

        if ($result !== null) {
            $this->delete($result);
        }
        return $result;
    }

}
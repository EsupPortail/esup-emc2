<?php

namespace Application\Service\CompetencesRetirees;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteCompetenceRetiree;
use Doctrine\ORM\NonUniqueResultException;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Competence;
use RuntimeException;

class CompetencesRetireesService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(FicheposteCompetenceRetiree $competenceRetiree): FicheposteCompetenceRetiree
    {
        $this->getObjectManager()->persist($competenceRetiree);
        $this->getObjectManager()->flush($competenceRetiree);
        return $competenceRetiree;
    }

    public function update(FicheposteCompetenceRetiree $competenceConservee): FicheposteCompetenceRetiree
    {
        $this->getObjectManager()->flush($competenceConservee);
        return $competenceConservee;
    }

    public function delete(FicheposteCompetenceRetiree $competenceConservee): FicheposteCompetenceRetiree
    {
        $this->getObjectManager()->remove($competenceConservee);
        $this->getObjectManager()->flush($competenceConservee);
        return $competenceConservee;
    }

    /** ACCESSEUR *****************************************************************************************************/

    public function getCompetenceRetiree(FichePoste $ficheposte, Competence $competence): ?FicheposteCompetenceRetiree
    {
        $qb = $this->getObjectManager()->getRepository(FicheposteCompetenceRetiree::class)->createQueryBuilder('retiree')
            ->andWhere('retiree.fichePoste = :ficheposte')
            ->andWhere('retiree.competence = :competence')
            ->setParameter('ficheposte', $ficheposte)
            ->setParameter('competence', $competence);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetencesRetirees ...", 0, $e);
        }
        return $result;

    }

    /**
     * @param FichePoste $ficheposte
     * @param Competence $competence
     * @return FicheposteCompetenceRetiree
     */
    public function add(FichePoste $ficheposte, Competence $competence): FicheposteCompetenceRetiree
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
    public function remove(FichePoste $ficheposte, Competence $competence): FicheposteCompetenceRetiree
    {
        $result = $this->getCompetenceRetiree($ficheposte, $competence);

        if ($result !== null) {
            $this->delete($result);
        }
        return $result;
    }

}
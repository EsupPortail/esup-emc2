<?php

namespace Application\Service\FormationsRetirees;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteFormationRetiree;
use Application\Entity\Db\Formation;
use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;

class FormationsRetireesService {
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteFormationRetiree $formationConservee
     * @return FicheposteFormationRetiree
     */
    public function create(FicheposteFormationRetiree $formationConservee) {
        $this->createFromTrait($formationConservee);
        return $formationConservee;
    }

    /**
     * @param FicheposteFormationRetiree $formationConservee
     * @return FicheposteFormationRetiree
     */
    public function update(FicheposteFormationRetiree $formationConservee) {
        $this->updateFromTrait($formationConservee);
        return $formationConservee;
    }

    /**
     * @param FicheposteFormationRetiree $formationConservee
     * @return FicheposteFormationRetiree
     */
    public function delete(FicheposteFormationRetiree $formationConservee) {
        $this->deleteFromTrait($formationConservee);
        return $formationConservee;
    }

    /** ACCESSEUR *****************************************************************************************************/

    /**
     * @param FichePoste $ficheposte
     * @param Formation $formation
     * @return FicheposteFormationRetiree
     */
    public function getFormationRetiree(FichePoste $ficheposte, Formation $formation)
    {
        $qb = $this->getEntityManager()->getRepository(FicheposteFormationRetiree::class)->createQueryBuilder('retiree')
            ->andWhere('retiree.fichePoste = :ficheposte')
            ->andWhere('retiree.formation = :formation')
            ->setParameter('ficheposte', $ficheposte)
            ->setParameter('formation', $formation);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationsRetirees ...",0,$e);
        }
        return $result;

    }

    /**
     * @param FichePoste $ficheposte
     * @param Formation $formation
     * @return FicheposteFormationRetiree
     */
    public function add(FichePoste $ficheposte, Formation $formation)
    {
        $result = $this->getFormationRetiree($ficheposte, $formation);

        if ($result === null) {
            $result = new FicheposteFormationRetiree();
            $result->setFichePoste($ficheposte);
            $result->setFormation($formation);
            $this->create($result);
        }
        return $result;
    }

    /**
     * @param FichePoste $ficheposte
     * @param Formation $formation
     * @return FicheposteFormationRetiree
     */
    public function remove(FichePoste $ficheposte, Formation $formation)
    {
        $result = $this->getFormationRetiree($ficheposte, $formation);

        if ($result !== null) {
            $this->delete($result);
        }
        return $result;
    }
}
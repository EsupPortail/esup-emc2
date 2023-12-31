<?php

namespace Application\Service\ActivitesDescriptionsRetirees;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteActiviteDescriptionRetiree;
use Doctrine\ORM\Exception\ORMException;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Mission;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class ActivitesDescriptionsRetireesService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteActiviteDescriptionRetiree $description
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function create(FicheposteActiviteDescriptionRetiree $description) : FicheposteActiviteDescriptionRetiree
    {
        try {
            $this->getEntityManager()->persist($description);
            $this->getEntityManager()->flush($description);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base", 0, $e);
        }
        return $description;
    }

    /**
     * @param FicheposteActiviteDescriptionRetiree $description
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function update(FicheposteActiviteDescriptionRetiree $description) : FicheposteActiviteDescriptionRetiree
    {
        try {
            $this->getEntityManager()->flush($description);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base", 0, $e);
        }
        return $description;
    }

    /**
     * @param FicheposteActiviteDescriptionRetiree $description
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function delete(FicheposteActiviteDescriptionRetiree $description) : FicheposteActiviteDescriptionRetiree
    {
        try {
            $this->getEntityManager()->remove($description);
            $this->getEntityManager()->flush($description);
        } catch(ORMException $e) {
            throw new RuntimeException("Un problème est survenu lors de l'enregistrement en base", 0, $e);
        }
        return $description;
    }

    /** ACCESSEUR *****************************************************************************************************/

    /**
     * @param FichePoste $ficheposte
     * @param FicheMetier $fichemetier
     * @param Mission $mission
     * @return FicheposteActiviteDescriptionRetiree[]
     */

    public function getActivitesDescriptionsRetirees(FichePoste $ficheposte, FicheMetier $fichemetier, Mission $mission) : array
    {
        $qb = $this->getEntityManager()->getRepository(FicheposteActiviteDescriptionRetiree::class)->createQueryBuilder('description')
            ->addSelect('ficheposte')->join('description.fichePoste', 'ficheposte')
            ->addSelect('fichemetier')->join('description.ficheMetier', 'fichemetier')
            ->addSelect('activite')->join('description.activite', 'activite')
            ->andWhere('description.fichePoste = :ficheposte')
            ->setParameter('ficheposte', $ficheposte)
            ->andWhere('description.ficheMetier = :fichemetier')
            ->setParameter('fichemetier', $fichemetier)
            ->andWhere('description.activite = :activite')
            ->setParameter('activite', $mission);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}
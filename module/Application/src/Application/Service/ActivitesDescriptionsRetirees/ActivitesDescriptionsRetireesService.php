<?php

namespace Application\Service\ActivitesDescriptionsRetirees;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteActiviteDescriptionRetiree;
use DoctrineModule\Persistence\ProvidesObjectManager;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Mission;

class ActivitesDescriptionsRetireesService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteActiviteDescriptionRetiree $description
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function create(FicheposteActiviteDescriptionRetiree $description): FicheposteActiviteDescriptionRetiree
    {
        $this->getObjectManager()->persist($description);
        $this->getObjectManager()->flush($description);
        return $description;
    }

    /**
     * @param FicheposteActiviteDescriptionRetiree $description
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function update(FicheposteActiviteDescriptionRetiree $description): FicheposteActiviteDescriptionRetiree
    {
        $this->getObjectManager()->flush($description);
        return $description;
    }

    /**
     * @param FicheposteActiviteDescriptionRetiree $description
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function delete(FicheposteActiviteDescriptionRetiree $description): FicheposteActiviteDescriptionRetiree
    {
        $this->getObjectManager()->remove($description);
        $this->getObjectManager()->flush($description);
        return $description;
    }

    /** ACCESSEUR *****************************************************************************************************/

    /**
     * @param FichePoste $ficheposte
     * @param FicheMetier $fichemetier
     * @param Mission $mission
     * @return FicheposteActiviteDescriptionRetiree[]
     */

    public function getActivitesDescriptionsRetirees(FichePoste $ficheposte, FicheMetier $fichemetier, Mission $mission): array
    {
        $qb = $this->getObjectManager()->getRepository(FicheposteActiviteDescriptionRetiree::class)->createQueryBuilder('description')
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
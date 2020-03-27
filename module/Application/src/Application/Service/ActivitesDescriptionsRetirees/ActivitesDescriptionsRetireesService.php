<?php

namespace Application\Service\ActivitesDescriptionsRetirees;

use Application\Entity\Db\Activite;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\FicheposteActiviteDescriptionRetiree;
use Doctrine\ORM\ORMException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ActivitesDescriptionsRetireesService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FicheposteActiviteDescriptionRetiree $description
     * @return FicheposteActiviteDescriptionRetiree
     */
    public function create($description)
    {
        $description->updateCreation($this->getUserService());
        $description->updateModification($this->getUserService());

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
    public function update($description)
    {
        $description->updateCreation($this->getUserService());
        $description->updateModification($this->getUserService());

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
    public function delete($description)
    {
        $description->updateCreation($this->getUserService());
        $description->updateModification($this->getUserService());

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
     * @param Activite $activite
     * @return FicheposteActiviteDescriptionRetiree[]
     */

    public function getActivitesDescriptionsRetirees(FichePoste $ficheposte, FicheMetier $fichemetier, Activite $activite)
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
            ->setParameter('activite', $activite);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

}
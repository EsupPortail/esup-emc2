<?php

namespace Application\Service\Fonction;

use Application\Entity\Db\Fonction;
use Doctrine\ORM\NonUniqueResultException;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FonctionService {
    use EntityManagerAwareTrait;

    /** Site **********************************************************************************************************/

    /**
     * @return Fonction[]
     */
    public function getFonctions()
    {
        $qb = $this->getEntityManager()->getRepository(Fonction::class)->createQueryBuilder('fonction')
            ->addSelect('libelle')->leftJoin('fonction.libelles','libelle')
            ->orderBy('fonction.parent, fonction.id')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @return array
     */
    public function getFonctionsAsOptions()
    {
        $fonctions = $this->getFonctions();
        $options = [];
        foreach ($fonctions as $fonction) {
            $array = [];
            foreach ($fonction->getLibelles() as $libelle) $array[] = $libelle->getLibelle();
            $options[$fonction->getId()] = implode("/", $array);
        }
        return $options;
    }

    /**
     * @param string $id
     * @return Fonction
     */
    public function getFonction($id)
    {
        $qb = $this->getEntityManager()->getRepository(Fonction::class)->createQueryBuilder('fonction')
            ->addSelect('libelle')->leftJoin('fonction.libelles','libelle')
            ->andWhere('fonction.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs Fonction partagent le même id ['.$id.']', $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Fonction
     */
    public function getRequestedSite($controller, $paramName)
    {
        $id = $controller->params()->fromRoute($paramName);
        $site = $this->getFonction($id);
        return $site;
    }
}

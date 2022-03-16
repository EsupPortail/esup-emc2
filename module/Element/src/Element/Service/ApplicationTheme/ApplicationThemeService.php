<?php

namespace Element\Service\ApplicationTheme;

use Application\Service\RendererAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\ApplicationTheme;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ApplicationThemeService {
    use EntityManagerAwareTrait;
    use RendererAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param ApplicationTheme $groupe
     * @return ApplicationTheme
     */
    public function create(ApplicationTheme $groupe) : ApplicationTheme
    {
        try {
            $this->getEntityManager()->persist($groupe);
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /**
     * @param ApplicationTheme $groupe
     * @return ApplicationTheme
     */
    public function update(ApplicationTheme $groupe) : ApplicationTheme
    {
        try {
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /**
     * @param ApplicationTheme $groupe
     * @return ApplicationTheme
     */
    public function historise(ApplicationTheme $groupe) : ApplicationTheme
    {
        try {
            $groupe->historiser();
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /**
     * @param ApplicationTheme $groupe
     * @return ApplicationTheme
     */
    public function restore(ApplicationTheme $groupe) : ApplicationTheme
    {
        try {
            $groupe->dehistoriser();
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /**
     * @param ApplicationTheme $groupe
     * @return ApplicationTheme
     */
    public function delete(ApplicationTheme $groupe) : ApplicationTheme
    {
        try {
            $this->getEntityManager()->remove($groupe);
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(ApplicationTheme::class)->createQueryBuilder('groupe')
             ->addSelect('application')->leftJoin('groupe.applications', 'application')
            ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return ApplicationTheme[]
     */
    public function getApplicationsGroupes($champ = 'ordre', $ordre='ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('groupe.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param ApplicationTheme $groupe
     * @return array
     */
    public function optionify(ApplicationTheme $groupe) {
        $this_option = [
            'value' =>  $groupe->getId(),
            'label' => $groupe->getLibelle(),
        ];
        return $this_option;
    }

    /**
     * @return array
     */
    public function getApplicationsGroupesAsOption()
    {
        $groupes = $this->getApplicationsGroupes();
        $array = [];
        foreach ($groupes as $groupe) {
            $option = $this->optionify($groupe);
            $array[$groupe->getId()] = $option;
        }
        return $array;
    }

    /**
     * @param integer $id
     * @return ApplicationTheme
     */
    public function getApplicationTheme(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('groupe.id = :id')
            ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ApplicationTheme paratagent le même id [".$id."]");
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return ApplicationTheme
     */
    public function getRequestedApplicationTheme(AbstractActionController $controller, $param = 'application-groupe')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getApplicationTheme($id);
        return $result;
    }
}
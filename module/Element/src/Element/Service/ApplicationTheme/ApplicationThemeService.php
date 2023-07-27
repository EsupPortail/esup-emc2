<?php

namespace Element\Service\ApplicationTheme;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\ApplicationTheme;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class ApplicationThemeService {
    use EntityManagerAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

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

    public function update(ApplicationTheme $groupe) : ApplicationTheme
    {
        try {
            $this->getEntityManager()->flush($groupe);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $groupe;
    }

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

    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(ApplicationTheme::class)->createQueryBuilder('groupe')
             ->addSelect('application')->leftJoin('groupe.applications', 'application')
            ;
        return $qb;
    }

    /** @return ApplicationTheme[] */
    public function getApplicationsGroupes(string $champ = 'ordre', string $ordre='ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('groupe.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function optionify(ApplicationTheme $groupe) : array
    {
        $this_option = [
            'value' =>  $groupe->getId(),
            'label' => $groupe->getLibelle(),
        ];
        return $this_option;
    }

    public function getApplicationsGroupesAsOption() : array
    {
        $groupes = $this->getApplicationsGroupes();
        $array = [];
        foreach ($groupes as $groupe) {
            $option = $this->optionify($groupe);
            $array[$groupe->getId()] = $option;
        }
        return $array;
    }

    public function getApplicationTheme(?int $id) : ?ApplicationTheme
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

    public function getRequestedApplicationTheme(AbstractActionController $controller, string $param = 'application-groupe') : ?ApplicationTheme
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getApplicationTheme($id);
        return $result;
    }
}
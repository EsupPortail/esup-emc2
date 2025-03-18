<?php

namespace Element\Service\ApplicationTheme;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\ApplicationTheme;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;

class ApplicationThemeService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(ApplicationTheme $groupe): ApplicationTheme
    {
        $this->getObjectManager()->persist($groupe);
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    public function update(ApplicationTheme $groupe): ApplicationTheme
    {
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    public function historise(ApplicationTheme $groupe): ApplicationTheme
    {
        $groupe->historiser();
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    public function restore(ApplicationTheme $groupe): ApplicationTheme
    {
        $groupe->dehistoriser();
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    public function delete(ApplicationTheme $groupe): ApplicationTheme
    {
        $this->getObjectManager()->remove($groupe);
        $this->getObjectManager()->flush($groupe);
        return $groupe;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ApplicationTheme::class)->createQueryBuilder('groupe')
            ->addSelect('application')->leftJoin('groupe.applications', 'application');
        return $qb;
    }

    /** @return ApplicationTheme[] */
    public function getApplicationsGroupes(string $champ = 'ordre', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('groupe.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function optionify(ApplicationTheme $groupe): array
    {
        $this_option = [
            'value' => $groupe->getId(),
            'label' => $groupe->getLibelle(),
        ];
        return $this_option;
    }

    public function getApplicationsGroupesAsOption(): array
    {
        $groupes = $this->getApplicationsGroupes();
        $array = [];
        foreach ($groupes as $groupe) {
            $option = $this->optionify($groupe);
            $array[$groupe->getId()] = $option;
        }
        return $array;
    }

    public function getApplicationTheme(?int $id): ?ApplicationTheme
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('groupe.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ApplicationTheme paratagent le même id [" . $id . "]",0,$e);
        }
        return $result;
    }

    public function getRequestedApplicationTheme(AbstractActionController $controller, string $param = 'application-groupe'): ?ApplicationTheme
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getApplicationTheme($id);
        return $result;
    }
}
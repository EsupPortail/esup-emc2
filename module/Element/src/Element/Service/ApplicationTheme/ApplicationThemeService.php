<?php

namespace Element\Service\ApplicationTheme;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\ApplicationTheme;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ApplicationThemeService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(ApplicationTheme $theme): ApplicationTheme
    {
        $this->getObjectManager()->persist($theme);
        $this->getObjectManager()->flush($theme);
        return $theme;
    }

    public function update(ApplicationTheme $theme): ApplicationTheme
    {
        $this->getObjectManager()->flush($theme);
        return $theme;
    }

    public function historise(ApplicationTheme $theme): ApplicationTheme
    {
        $theme->historiser();
        $this->getObjectManager()->flush($theme);
        return $theme;
    }

    public function restore(ApplicationTheme $theme): ApplicationTheme
    {
        $theme->dehistoriser();
        $this->getObjectManager()->flush($theme);
        return $theme;
    }

    public function delete(ApplicationTheme $theme): ApplicationTheme
    {
        $this->getObjectManager()->remove($theme);
        $this->getObjectManager()->flush($theme);
        return $theme;
    }

    /** REQUETAGE *****************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(ApplicationTheme::class)->createQueryBuilder('theme')
            ->addSelect('application')->leftJoin('theme.applications', 'application');
        return $qb;
    }

    /** @return ApplicationTheme[] */
    public function getApplicationsThemes(string $champ = 'ordre', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('theme.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function optionify(ApplicationTheme $theme): array
    {
        $this_option = [
            'value' => $theme->getId(),
            'label' => $theme->getLibelle(),
        ];
        return $this_option;
    }

    public function getApplicationsGroupesAsOption(): array
    {
        $themes = $this->getApplicationsThemes();
        $array = [];
        foreach ($themes as $theme) {
            $option = $this->optionify($theme);
            $array[$theme->getId()] = $option;
        }
        return $array;
    }

    public function getApplicationTheme(?int $id): ?ApplicationTheme
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('theme.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".ApplicationTheme::class."] paratagent le même id [" . $id . "]",0,$e);
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
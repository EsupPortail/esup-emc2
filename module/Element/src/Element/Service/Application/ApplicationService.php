<?php

namespace Element\Service\Application;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationTheme;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ApplicationService
{
    use ProvidesObjectManager;

    /** GESTION DE L'ENTITÉ *******************************************************************************************/

    public function create(Application $application): Application
    {
        $application->setActif(true);
        $this->getObjectManager()->persist($application);
        $this->getObjectManager()->flush($application);
        return $application;
    }

    public function update(Application $application): Application
    {
        $this->getObjectManager()->flush($application);
        return $application;
    }

    public function historise(Application $application): Application
    {
        $application->historiser();
        $this->getObjectManager()->flush($application);
        return $application;
    }

    public function restore(Application $application): Application
    {
        $application->dehistoriser();
        $this->getObjectManager()->flush($application);
        return $application;
    }

    public function delete(Application $application): Application
    {
        $this->getObjectManager()->remove($application);
        $this->getObjectManager()->flush();
        return $application;
    }

    /** REQUETES ******************************************************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Application::class)->createQueryBuilder('application')
            ->addSelect('theme')->leftJoin('application.theme', 'theme');
        return $qb;
    }

    /** @return Application[] */
    public function getApplications(string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('application.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getApplicationsAsOptions(string $champ = 'libelle', string $ordre = 'ASC'): array
    {
        $result = $this->getApplications($champ, $ordre);
        $array = [];
        foreach ($result as $item) {
            $array[$item->getId()] = $this->applicationOptionify($item);
        }

        return $array;
    }

    /**  @return Application[] */
    public function getApplicationsGyGroupe(?ApplicationTheme $theme): array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('application.libelle');
        if ($theme) {
            $qb = $qb->andWhere('theme.id = :themeId')
                ->setParameter('themeId', $theme->getId());
        } else {
            $qb = $qb->andWhere('theme IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function getApplication(?int $id): ?Application
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('application.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs applications portent le même identifiant [' . $id . ']', 0, $e);
        }
        return $result;
    }

    public function getRequestedApplication(AbstractActionController $controller, string $paramName = 'application'): ?Application
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getApplication($id);
    }


    public function getApplicationByLibelle(string $libelle): ?Application
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('LOWER(application.libelle) LIKE :trim')
            ->setParameter('trim', '%' . strtolower($libelle) . '%');
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".Application::class."] partage le même libellé [".$libelle."]", 0, $e);
        }
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    private function applicationOptionify(Application $application): array
    {
        $theme = $application->getTheme();
        $texte = $application->getLibelle();
        $this_option = [
            'value' => $application->getId(),
            'attributes' => [
                'data-content' => "<span class='application' title='".($application->getDescription()??"Aucune description")."' class='badge btn-danger'>" . $texte
                    . "&nbsp;" . "<span class='badge'>"
                    . (($theme !== null) ? $theme->getLibelle() : "Sans thème")
                    . "</span>"
                    . "<span class='description' style='display: none' onmouseenter='alert(event.target);'>".($application->getDescription()??"Aucune description")."</span>"
                    ."</span>",
            ],
            'label' => $texte,
        ];
        return $this_option;
    }

    public function generateDictionnaire(): array
    {
        $applications = $this->getApplications();
        $dictionnaire = [];

        foreach ($applications as $application) {
            $dictionnaire[$application->getLibelle()] = $application;
        }
        return $dictionnaire;
    }

}
<?php

namespace Application\Service\Configuration;

use Application\Entity\Db\ConfigurationEntretienProfessionnel;
use Application\Entity\Db\ConfigurationFicheMetier;
use Doctrine\ORM\NonUniqueResultException;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class ConfigurationService
{
    use ProvidesObjectManager;
    use ApplicationElementServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    public function create(ConfigurationFicheMetier|ConfigurationEntretienProfessionnel $configuration): ConfigurationFicheMetier|ConfigurationEntretienProfessionnel
    {
        $this->getObjectManager()->persist($configuration);
        $this->getObjectManager()->flush($configuration);
        return $configuration;
    }

    public function update(ConfigurationFicheMetier|ConfigurationEntretienProfessionnel $configuration): ConfigurationFicheMetier|ConfigurationEntretienProfessionnel
    {
        $this->getObjectManager()->flush($configuration);
        return $configuration;
    }

    public function delete(ConfigurationFicheMetier|ConfigurationEntretienProfessionnel $configuration): ConfigurationFicheMetier|ConfigurationEntretienProfessionnel
    {
        $this->getObjectManager()->remove($configuration);
        $this->getObjectManager()->flush($configuration);
        return $configuration;
    }

    /** CONFIGU *******************************************************************************************************/

    /**
     * @param ?string $entityType (parmi Application::class, Formation::class et Competence::class)
     * @return ConfigurationFicheMetier[]
     */
    public function getConfigurationsFicheMetier(?string $entityType = null): array
    {

        $qb = $this->getObjectManager()->getRepository(ConfigurationFicheMetier::class)->createQueryBuilder('configuration');

        if ($entityType !== null) {
            $qb = $qb->andWhere('configuration.entityType = :type')
                ->setParameter('type', $entityType);
        }
        $result = $qb->getQuery()->getResult();

        /** @var ConfigurationFicheMetier $item */
        foreach ($result as $item) {
            $qbe = $this->getObjectManager()->getRepository($item->getEntityType())->createQueryBuilder('item')
                ->andWhere('item.id = :id')
                ->setParameter('id', $item->getEntityId());
            try {
                $ree = $qbe->getQuery()->getOneOrNullResult();
            } catch (NonUniqueResultException $e) {
                throw new RuntimeException("Plusieurs " . $item->getEntityType() . " partagent le même identifiant [" . $item->getEntityId() . "]", 0, $e);
            }
            $item->setEntity($ree);
        }
        return $result;
    }

    public function getConfigurationFicheMetier(?int $id): ?ConfigurationFicheMetier
    {
        $qb = $this->getObjectManager()->getRepository(ConfigurationFicheMetier::class)->createQueryBuilder('configuration')
            ->andWhere('configuration.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ConfigurationFicheMetier partagent le même [" . $id . "]", 0, $e);
        }
        return $result;
    }

    public function getRequestedConfigurationFicheMetier(AbstractActionController $controller, string $paramName = 'configuration'): ?ConfigurationFicheMetier
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getConfigurationFicheMetier($id);
        return $result;
    }


    public function addDefaultToFicheMetier(FicheMetier $fiche, ?string $entity = null): FicheMetier
    {
        $ajouts = $this->getConfigurationsFicheMetier($entity);
        foreach ($ajouts as $ajout) {
            if ($ajout->getEntity() and $ajout->getEntityType() === Application::class and !$fiche->hasApplication($ajout->getEntity())) {
                $applicationElement = new ApplicationElement();
                $applicationElement->setApplication($ajout->getEntity());
                $this->getApplicationElementService()->create($applicationElement);
                $fiche->addApplicationElement($applicationElement);
            }
            if ($ajout->getEntity() and $ajout->getEntityType() === Competence::class and !$fiche->hasCompetence($ajout->getEntity())) {
                $competenceElement = new CompetenceElement();
                $competenceElement->setCompetence($ajout->getEntity());
                $this->getCompetenceElementService()->create($competenceElement);
                $fiche->addCompetenceElement($competenceElement);
            }
        }
        return $fiche;
    }

}

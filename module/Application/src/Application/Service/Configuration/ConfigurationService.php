<?php

namespace Application\Service\Configuration;

use Application\Entity\Db\ConfigurationEntretienProfessionnel;
use Application\Entity\Db\ConfigurationFicheMetier;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Element\Entity\Db\Application;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;

class ConfigurationService {
    use EntityManagerAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param ConfigurationFicheMetier|ConfigurationEntretienProfessionnel $configuration
     * @return ConfigurationFicheMetier|ConfigurationEntretienProfessionnel
     */
    public function create($configuration)
    {
        try {
            $this->getEntityManager()->persist($configuration);
            $this->getEntityManager()->flush($configuration);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $configuration;
    }

    /**
     * @param ConfigurationFicheMetier|ConfigurationEntretienProfessionnel $configuration
     * @return ConfigurationFicheMetier|ConfigurationEntretienProfessionnel
     */
    public function update($configuration)
    {
        try {
            $this->getEntityManager()->flush($configuration);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $configuration;
    }

    /**
     * @param ConfigurationFicheMetier|ConfigurationEntretienProfessionnel $configuration
     * @return ConfigurationFicheMetier|ConfigurationEntretienProfessionnel
     */
    public function delete($configuration)
    {
        try {
            $this->getEntityManager()->remove($configuration);
            $this->getEntityManager()->flush($configuration);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $configuration;
    }

    /** CONFIGU *******************************************************************************************************/

    /**
     * @param string $entityType (parmi Application::class, Formation::class et Competence::class)
     * @return ConfigurationFicheMetier[]
     */
    public function getConfigurationsFicheMetier($entityType = null) {

        $qb = $this->getEntityManager()->getRepository(ConfigurationFicheMetier::class)->createQueryBuilder('configuration')
        ;

        if ($entityType !== null) {
            $qb = $qb->andWhere('configuration.entityType = :type')
                ->setParameter('type', $entityType);
        }
        $result = $qb->getQuery()->getResult();

        /** @var ConfigurationFicheMetier $item */
        foreach ($result as $item) {
            $qbe = $this->getEntityManager()->getRepository($item->getEntityType())->createQueryBuilder('item')
                ->andWhere('item.id = :id')
                ->setParameter('id', $item->getEntityId());
            try {
                $ree = $qbe->getQuery()->getOneOrNullResult();
            } catch (NonUniqueResultException $e) {
                throw new RuntimeException("Plusieurs ".$item->getEntityType()." partagent le même identifiant [".$item->getEntityId()."]" , 0, $e);
            }
            $item->setEntity($ree);
        }
        return $result;
    }

    /**
     * @param integer $id
     * @return ConfigurationFicheMetier
     */
    public function getConfigurationFicheMetier($id)
    {
        $qb = $this->getEntityManager()->getRepository(ConfigurationFicheMetier::class)->createQueryBuilder('configuration')
            ->andWhere('configuration.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ConfigurationFicheMetier partagent le même [".$id."]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return ConfigurationFicheMetier
     */
    public function getRequestedConfigurationFicheMetier($controller, $paramName = 'configuration')
    {
        $id = $controller->params()->fromRoute($paramName);
        $result = $this->getConfigurationFicheMetier($id);
        return $result;
    }



    /**
     * @param FicheMetier $fiche
     * @return FicheMetier
     */
    public function addDefaultToFicheMetier(FicheMetier $fiche) : FicheMetier
    {
        $ajouts = $this->getConfigurationsFicheMetier();

        foreach ($ajouts as $ajout) {
            if ($ajout->getEntityType() === Application::class AND !$fiche->hasApplication($ajout->getEntity())) {
                $applicationElement = new ApplicationElement();
                $applicationElement->setApplication($ajout->getEntity());
                $this->getApplicationElementService()->create($applicationElement);
                $fiche->addApplicationElement($applicationElement);
            }
            if ($ajout->getEntityType() === Competence::class  AND !$fiche->hasCompetence($ajout->getEntity())) {
                $competenceElement = new CompetenceElement();
                $competenceElement->setCompetence($ajout->getEntity());
                $this->getCompetenceElementService()->create($competenceElement);
                $fiche->addCompetenceElement($competenceElement);
            }
        }
        return $fiche;
    }

    /** CONFIGURATION ENTRETIEN PRO ***********************************************************************************/

    /**
     * @return ConfigurationEntretienProfessionnel[]
     */
    public function getConfigurationsEntretienProfessionnel()
    {
        $qb = $this->getEntityManager()->getRepository(ConfigurationEntretienProfessionnel::class)->createQueryBuilder('configuration')
            ->andWhere('configuration.histoDestruction IS NULL')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return ConfigurationEntretienProfessionnel
     */
    public function getConfigurationEntretienProfessionnel($id)
    {
        $qb = $this->getEntityManager()->getRepository(ConfigurationEntretienProfessionnel::class)->createQueryBuilder('configuration')
            ->andWhere('configuration.id = :id')
            ->setParameter('id', $id)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs ConfigurationEntretienProfessionnel partagent le même id [". $id."]",0, $e);
        }
        return $result;
    }
}

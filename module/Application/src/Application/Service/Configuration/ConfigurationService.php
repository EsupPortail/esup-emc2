<?php

namespace Application\Service\Configuration;

use Application\Entity\Db\ConfigurationFicheMetier;
use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Exception;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Utilisateur\Service\User\UserServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class ConfigurationService {
    use EntityManagerAwareTrait;
    use UserServiceAwareTrait;

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
     * @param ConfigurationFicheMetier $configuration
     * @return ConfigurationFicheMetier
     */
    public function create($configuration)
    {
        try {
            $date = new DateTime();
            $user = $this->getUserService()->getConnectedUser();
        } catch (Exception $e) {
            throw new RuntimeException("Un problème s'est produit lors de la récupération des informations d'historisation", $e);
        }
        $configuration->setHistoCreation($date);
        $configuration->setHistoCreateur($user);
        $configuration->setHistoModification($date);
        $configuration->setHistoModificateur($user);

        try {
            $this->getEntityManager()->persist($configuration);
            $this->getEntityManager()->flush($configuration);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $configuration;
    }

    /**
     * @param ConfigurationFicheMetier $configuration
     * @return ConfigurationFicheMetier
     */
    public function delete($configuration)
    {
        try {
            $this->getEntityManager()->remove($configuration);
            $this->getEntityManager()->flush($configuration);
        } catch (ORMException $e) {
            throw new RuntimeException('Un problème est survenu lors de la création en BD', $e);
        }
        return $configuration;
    }
}
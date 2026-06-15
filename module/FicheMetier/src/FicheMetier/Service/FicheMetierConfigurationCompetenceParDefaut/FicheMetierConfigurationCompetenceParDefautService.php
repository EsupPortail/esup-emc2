<?php

namespace FicheMetier\Service\FicheMetierConfigurationCompetenceParDefaut;


use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierConfigurationCompetenceParDefaut;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use RuntimeException;

class FicheMetierConfigurationCompetenceParDefautService
{
    use ProvidesObjectManager;
    use CompetenceElementServiceAwareTrait;
    use FicheMetierServiceAwareTrait;

    /** GESTION DES ENTITÉS ***********************************************/

    public function create(FicheMetierConfigurationCompetenceParDefaut $competenceParDefaut): void
    {
        $this->getObjectManager()->persist($competenceParDefaut);
        $this->getObjectManager()->flush($competenceParDefaut);
    }

    public function update(FicheMetierConfigurationCompetenceParDefaut $competenceParDefaut): void
    {
        $this->getObjectManager()->flush($competenceParDefaut);
    }

    public function historise(FicheMetierConfigurationCompetenceParDefaut $competenceParDefaut): void
    {
        $competenceParDefaut->historiser();
        $this->getObjectManager()->flush($competenceParDefaut);
    }

    public function restore(FicheMetierConfigurationCompetenceParDefaut $competenceParDefaut): void
    {
        $competenceParDefaut->dehistoriser();
        $this->getObjectManager()->flush($competenceParDefaut);
    }

    public function delete(FicheMetierConfigurationCompetenceParDefaut $competenceParDefaut): void
    {
        $this->getObjectManager()->remove($competenceParDefaut);
        $this->getObjectManager()->flush($competenceParDefaut);
    }

    /** QUERYING *******************************************************************/

    public function createQueryBuilder(): QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(FicheMetierConfigurationCompetenceParDefaut::class)->createQueryBuilder('competenceParDefaut')
            ->addSelect('competence')->join('competenceParDefaut.competence', 'competence')
        ;
        return $qb;
    }

    public function getFicheMetierConfigurationCompetenceParDefaut(?int $id): ?FicheMetierConfigurationCompetenceParDefaut
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.id = :id')->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs [".FicheMetierConfigurationCompetenceParDefaut::class."] partagent le même id [".$id."]",0,$e);
        }
        return $result;
    }

    public function getRequestedFicheMetierConfigurationCompetenceParDefaut(AbstractActionController $controller, string $param='competence-par-defaut'): ?FicheMetierConfigurationCompetenceParDefaut
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFicheMetierConfigurationCompetenceParDefaut($id);
        return $result;
    }

    /** @return FicheMetierConfigurationCompetenceParDefaut[] */
    public function getFicheMetierConfigurationCompetencesParDefaut(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) {
            $qb = $qb->andWhere('competenceParDefaut.histoDestruction IS NULL');
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FACADE ********************************************************************************************************/

    public function hasCompetence(?Competence $competence): bool
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('competence.id = :id')->setParameter('id', $competence->getId());
        ;
        $result = $qb->getQuery()->getResult();
        return !empty($result);
    }

    public function applyDefault(FicheMetier $ficheMetier): void
    {
        $competences = $this->getFicheMetierConfigurationCompetencesParDefaut();
        foreach ($competences as $competence) {
            if (!$ficheMetier->hasCompetence($competence->getCompetence())) {
                $element = new CompetenceElement();
                $element->setCompetence($competence->getCompetence());
                $this->getCompetenceElementService()->create($element);
                $ficheMetier->addCompetenceElement($element);
            }
        }
        $this->getFicheMetierService()->update($ficheMetier);
    }
}

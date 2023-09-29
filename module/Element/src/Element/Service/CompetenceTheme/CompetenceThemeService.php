<?php

namespace Element\Service\CompetenceTheme;

use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\QueryBuilder;
use Element\Entity\Db\CompetenceTheme;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;

class CompetenceThemeService {
    use EntityManagerAwareTrait;

    /** ENTITY MANAGMENT **********************************************************************************************/

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function create(CompetenceTheme $theme) : CompetenceTheme
    {
        try {
            $this->getEntityManager()->persist($theme);
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function update(CompetenceTheme $theme) : CompetenceTheme
    {
        try {
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function historise(CompetenceTheme $theme) : CompetenceTheme
    {
        try {
            $theme->historiser();
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function restore(CompetenceTheme $theme) : CompetenceTheme
    {
        try {
            $theme->dehistoriser();
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /**
     * @param CompetenceTheme $theme
     * @return CompetenceTheme
     */
    public function delete(CompetenceTheme $theme) : CompetenceTheme
    {
        try {
            $this->getEntityManager()->remove($theme);
            $this->getEntityManager()->flush($theme);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en BD.", $e);
        }
        return $theme;
    }

    /** REQUETE *******************************************************************************************************/

    public function createQueryBuilder() : QueryBuilder
    {
        try {
            $qb = $this->getEntityManager()->getRepository(CompetenceTheme::class)->createQueryBuilder('theme')
                ->addSelect('competence')->leftJoin('theme.competences', 'competence');
        } catch (NotSupported $e) {
            throw new RuntimeException("Un problème est survenu lors de la creation que QueryBuilder de [".QueryBuilder::class."]",0, $e);
        }
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return CompetenceTheme[]
     */
    public function getCompetencesThemes(string $champ = 'libelle', string $order = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('theme.'.$champ, $order)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $champ
     * @param string $order
     * @return array
     */
    public function getCompetencesThemesAsOptions(string $champ = 'libelle', string $order = 'ASC') : array
    {
        $types = $this->getCompetencesThemes($champ, $order);
        $options = [];
        foreach ($types as $type) {
            $options[$type->getId()] = $type->getLibelle();
        }
        return $options;
    }

    /**
     * @param int|null $id
     * @return CompetenceTheme|null
     */
    public function getCompetenceTheme(?int $id) : ?CompetenceTheme
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('theme.id = :id') ->setParameter('id', $id)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch(NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs CompetenceTheme partagent le même id [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return CompetenceTheme|null
     */
    public function getRequestedCompetenceTheme(AbstractActionController $controller, string  $paramName = 'competence-theme') : ?CompetenceTheme
    {
        $id = $controller->params()->fromRoute($paramName);
        $theme = $this->getCompetenceTheme($id);
        return $theme;
    }

    /**
     * @param string $libelle
     * @return CompetenceTheme|null
     */
    public function getCompetenceThemeByLibelle(string $libelle) : ?CompetenceTheme
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('theme.libelle = :libelle') ->setParameter('libelle', $libelle)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException('Plusieurs CompetenceTheme partagent le même libellé ['.$libelle.']',0,$e);
        }
        return $result;
    }

    /** FACADE ***********************************************************************/

    public function createWith(?string $libelle) : CompetenceTheme
    {
        $theme = new CompetenceTheme();
        $theme->setLibelle($libelle);
        $this->create($theme);
        return $theme;
    }

}

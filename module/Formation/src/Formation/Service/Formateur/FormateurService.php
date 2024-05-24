<?php

namespace Formation\Service\Formateur;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use DoctrineModule\Persistence\ProvidesObjectManager;
use Formation\Entity\Db\Formateur;
use UnicaenApp\Exception\RuntimeException;
use Laminas\Mvc\Controller\AbstractActionController;
use UnicaenUtilisateur\Entity\Db\User;

class FormateurService
{
    use ProvidesObjectManager;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function create(Formateur $formateur) : Formateur
    {
        $this->getObjectManager()->persist($formateur);
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function update(Formateur $formateur) : Formateur
    {
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function historise(Formateur $formateur) : Formateur
    {
        $formateur->historiser();
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function restore(Formateur $formateur) : Formateur
    {
        $formateur->dehistoriser();
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /**
     * @param Formateur $formateur
     * @return Formateur
     */
    public function delete(Formateur $formateur) : Formateur
    {
        $this->getObjectManager()->remove($formateur);
        $this->getObjectManager()->flush($formateur);
        return $formateur;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getObjectManager()->getRepository(Formateur::class)->createQueryBuilder('formateur')
            ->addSelect('session')->leftjoin('formateur.sessions', 'session')
            ->addSelect('utilisateur')->leftjoin('formateur.utilisateur', 'utilisateur')
        ;
        return $qb;
    }

    /**
     * @param int|null $id
     * @return Formateur|null
     */
    public function getFormateur(?int $id) : ?Formateur
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formateur.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Formateur partagent le même id [" . $id . "]",0,$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return Formateur|null
     */
    public function getRequestedFormateur(AbstractActionController $controller, string $param = 'formateur') : ?Formateur
    {
        $id = $controller->params()->fromRoute($param);
        return $this->getFormateur($id);
    }

    /** @return Formateur[] */
    public function getFormateurs(bool $withHisto = false): array
    {
        $qb = $this->createQueryBuilder();
        if (!$withHisto) $qb = $qb->andWhere('formateur.histoDestruction IS NULL');

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Formateur[] */
    public function getFormateursWithFiltre(array $params): array
    {
        $type = $params['type']??null;
        $formateurId = isset($params['formateur'])?$params['formateur']['id']:null;
        $rattachement = isset($params['rattachement'])?$params['rattachement']['label']:null;

        $qb = $this->createQueryBuilder();
        if ($type) $qb = $qb->andWhere('formateur.type = :type')->setParameter('type', $type);
        if ($formateurId) $qb = $qb->andWhere('formateur.id = :formateurId')->setParameter('formateurId', $formateurId);
        if ($rattachement) $qb = $qb->andWhere('formateur.attachement = :rattachement')->setParameter('rattachement', $rattachement);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** Fonctions liées aux recherches ********************************************************************************/

    /** @return Formateur[] */
    public function getFormateursByTerm(?string $term): array
    {
        $qb = $this->getObjectManager()->getRepository(Formateur::class)->createQueryBuilder('formateur')
            ->andWhere("LOWER(CONCAT(formateur.nom, ' ', formateur.prenom)) like :search OR LOWER(CONCAT(formateur.prenom, ' ', formateur.nom)) like :search OR LOWER(formateur.organisme) like :search")
            ->setParameter('search', '%'.strtolower($term).'%')
//            ->orderBy("coalesce(formateur.organisme, concat(formateur.nom, ' ', formateur.prenom))", 'ASC')
            ->orderBy("concat(formateur.nom, ' ', formateur.prenom)", 'ASC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function formatFormateursJSON(array $formateurs): array
    {
        $result = [];
        /** @var Formateur[] $formateurs */
        foreach ($formateurs as $formateur) {
            $extra = ($formateur->getEmail()) ?? "Aucune adresse électronique connue";
            $result[] = array(
                'id' => $formateur->getId(),
                'label' => $formateur->getDenomination(),
                'extra' => "<span class='badge' style='background-color: slategray;'>" . $extra . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /** @return Formateur[] */
    public function getRattachementByTerm(?string $term): array
    {
        $qb = $this->getObjectManager()->getRepository(Formateur::class)->createQueryBuilder('formateur')
            ->andWhere("LOWER(formateur.attachement) like :search")
            ->setParameter('search', '%'.strtolower($term).'%')
            ->orderBy('formateur.attachement', 'ASC')
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function formatRattachementsJSON(array $formateurs): array
    {
        $result = [];
        /** @var Formateur[] $formateurs */
        foreach ($formateurs as $formateur) {
//            $extra = ($formateur->getEmail()) ?? "Aucune adresse électronique connue";
            $result[] = array(
                'id' => $formateur->getId(),
                'label' => $formateur->getAttachement(),
//                'extra' => "<span class='badge' style='background-color: slategray;'>" . $extra . "</span>",
            );
        }
        usort($result, function ($a, $b) {
            return strcmp($a['label'], $b['label']);
        });
        return $result;
    }

    /** Gestion pour les rôles automatiques ***************************************************************************/

    public function getUsersInFormateur() : array
    {
        $qb = $this->createQueryBuilder();
        $result = $qb->getQuery()->getResult();

        $users = [];
        /** @var Formateur $item */
        foreach ($result as $item) {
            $users[] = $item->getUtilisateur();
        }
        return $users;
    }

    /** @return Formateur[] */
    public function getFormateursByUser(?User $user) : array
    {
        if ($user === null) return [];

        $qb = $this->createQueryBuilder()
            ->andWhere('formateur.histoDestruction IS NULL')
            ->andWhere('formateur.utilisateur = :user')
            ->setParameter('user', $user)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** @return Formateur[] */
    public function getFormateursByEmail(string $email): array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('formateur.email = :email')
            ->setParameter('email', $email)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
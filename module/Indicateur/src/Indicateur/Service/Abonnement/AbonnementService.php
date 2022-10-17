<?php

namespace Indicateur\Service\Abonnement;

use DateTime;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;
use Indicateur\Entity\Db\Abonnement;
use Indicateur\Entity\Db\Indicateur;
use Indicateur\Service\Indicateur\IndicateurServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenApp\Service\EntityManagerAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use Laminas\Mvc\Controller\AbstractActionController;

class AbonnementService {
    use EntityManagerAwareTrait;
    use IndicateurServiceAwareTrait;
    use MailServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param Abonnement $abonnement
     * @return Abonnement
     */
    public function create(Abonnement $abonnement) : Abonnement
    {
        try {
            $this->getEntityManager()->persist($abonnement);
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $abonnement;
    }

    /**
     * @param Abonnement $abonnement
     * @return Abonnement
     */
    public function update(Abonnement $abonnement) : Abonnement
    {
        try {
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $abonnement;
    }

    /**
     * @param Abonnement $abonnement
     * @return Abonnement
     */
    public function delete(Abonnement $abonnement) : Abonnement
    {
        try {
            $this->getEntityManager()->remove($abonnement);
            $this->getEntityManager()->flush($abonnement);
        } catch (ORMException $e) {
            throw new RuntimeException("Un problème est survenue lors de l'enregistrement en base.", $e);
        }
        return $abonnement;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
    {
        $qb = $this->getEntityManager()->getRepository(Abonnement::class)->createQueryBuilder('abonnement')
            ->join('abonnement.user', 'user')->addSelect('user')
            ->join('abonnement.indicateur', 'indicateur')->addSelect('indicateur')
        ;
        return $qb;
    }

    /**
     * @param string $attribut
     * @param string $ordre
     * @return Abonnement[]
     */
    public function getAbonnements(string $attribut = 'id', string $ordre = 'ASC') : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('abonnement.' . $attribut, $ordre)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param int|null $id
     * @return Abonnement|null
     */
    public function getAbonnement(?int $id) : ?Abonnement
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('abonnement.id = :id')
            ->setParameter('id', $id)
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs Abonnement partagent le même id [".$id."]",$e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return Abonnement|null
     */
    public function getRequestedAbonnement(AbstractActionController $controller, string $paramName='abonnement') : ?Abonnement
    {
        $id = $controller->params()->fromRoute($paramName);
        return $this->getAbonnement($id);
    }

    /**
     * @param User
     * @return Abonnement[]
     */
    public function getAbonnementsByUser($user) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('abonnement.user = :user')
            ->setParameter('user', $user)
            ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param User $user
     * @param Indicateur $indicateur
     * @return Abonnement[]
     */
    public function getAbonnementsByUserAndIndicateur(User $user, Indicateur $indicateur) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('abonnement.user = :user')
            ->andWhere('abonnement.indicateur = :indicateur')
            ->setParameter('user', $user)
            ->setParameter('indicateur', $indicateur)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /** FONCTIONNEMENT ************************************************************************************************/

    public function notifyAbonnements() {
        $indicateurs = $this->getIndicateurService()->getIndicateurs();

        foreach ($indicateurs as $indicateur) {
            $abonnements = $indicateur->getAbonnements();
            if (!empty($abonnements)) {

                $titre = "Publication de l'indicateur [".$indicateur->getTitre()."] (". (new DateTime())->format("d/m/Y à H:i:s").")";
                $result = $this->getIndicateurService()->getIndicateurData($indicateur);
                $texte  = "<table>";
                $texte .= "<thead>";
                $texte .= "<tr>";
                foreach ($result[0] as $rubrique) $texte .= "<th>" . $rubrique . "</th>";
                $texte .= "</tr>";
                $texte .= "</thead>";
                $texte .= "<tbody>";
                foreach ($result[1] as $item) {
                    $texte .="<tr>";
                    foreach ($item as $value) $texte .="<td>". $value ."</td>";
                    $texte .="</tr>";
                }
                $texte .= "</tbody>";
                $texte .= "</table>";

                foreach ($abonnements as $abonnement) {
                    $adresse = $abonnement->getUser()->getEmail();
                    $mail = $this->getMailService()->sendMail($adresse, $titre, $texte);
                    $mail->setEntity($indicateur);
                    $this->getMailService()->update($mail);
                    $abonnement->setDernierEnvoi(new DateTime());
                    $this->update($abonnement);
                }
            }
        }
    }

    /**
     * @param User|null $user
     * @param Indicateur|null $indicateur
     */
    public function isAbonner(?User $user, ?Indicateur $indicateur) : bool
    {
        foreach ($indicateur->getAbonnements() as $abonnement) {
            if ($abonnement->getUser() === $user) return true;
        }
        return false;
    }

}
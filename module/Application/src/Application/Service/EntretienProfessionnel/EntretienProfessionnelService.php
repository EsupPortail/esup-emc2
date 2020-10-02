<?php

namespace Application\Service\EntretienProfessionnel;

use Application\Entity\Db\Agent;
use Application\Entity\Db\EntretienProfessionnel;
use Application\Entity\Db\EntretienProfessionnelCampagne;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\GestionEntiteHistorisationTrait;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Ramsey\Uuid\Uuid;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class EntretienProfessionnelService {
//    use DateTimeAwareTrait;
//    use EntityManagerAwareTrait;
//    use UserServiceAwareTrait;
    use GestionEntiteHistorisationTrait;
    use ConfigurationServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;
    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function create($entretien)
    {
        $this->createFromTrait($entretien);
        $this->generateToken($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function update($entretien)
    {
        $this->updateFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function historise($entretien)
    {
        $this->historiserFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function restore($entretien)
    {
        $this->restoreFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function delete($entretien)
    {
        $this->deleteFromTrait($entretien);
        return $entretien;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(EntretienProfessionnel::class)->createQueryBuilder('entretien')
            ->addSelect('agent')->join('entretien.agent', 'agent')
            ->addSelect('responsable')->join('entretien.responsable', 'responsable')
            ->addSelect('campagne')->join('entretien.campagne', 'campagne')
            ->addSelect('formulaireInstance')->join('entretien.formulaireInstance', 'formulaireInstance')
            ->addSelect('reponse')->leftJoin('formulaireInstance.reponses', 'reponse')
            ->addSelect('formulaire')->join('formulaireInstance.formulaire', 'formulaire')
            ->addSelect('categorie')->join('formulaire.categories', 'categorie')
            ->addSelect('champ')->join('categorie.champs', 'champ')
            //todo recupérer les validations
        ;
        return $qb;
    }

    /**
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels()
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.annee, agent.nomUsuel, agent.prenom');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return EntretienProfessionnel
     */
    public function getEntretienProfessionnel($id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.id = :id')
            ->setParameter('id', $id);
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnel partagent le même identifiant [".$id."]", $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $paramName
     * @return EntretienProfessionnel
     */
    public function getRequestedEntretienProfessionnel($controller, $paramName = 'entretien-professionnel')
    {
        $id = $controller->params()->fromRoute($paramName);
        $entretien = $this->getEntretienProfessionnel($id);
        return $entretien;
    }

    /**
     * @param Agent $agent
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnelsParAgent($agent)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.agent = :agent')
            ->setParameter('agent', $agent)
            ->orderBy('campagne.annee', 'ASC')
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string token
     * @return EntretienProfessionnel
     */
    public function getEntretiensProfessionnelsByToken(string $token)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.token = :token')
            ->setParameter('token', $token)
        ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs EntretienProfessionnel partagent le même token [".$token."]", $e);
        }
        return $result;
    }

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel
     */
    public function getPreviousEntretienProfessionnel(EntretienProfessionnel $entretien)
    {
        $agent = $entretien->getAgent();
        $date = $entretien->getDateEntretien();

        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.agent = :agent')
            ->andWhere('entretien.dateEntretien < :date')
            ->setParameter('agent', $agent)
            ->setParameter('date', $date)
            ->orderBy('entretien.dateEntretien', 'DESC');
        $result = $qb->getQuery()->getResult();

        if ($result === null) return null;
        return $result[0];
    }

    public function getEntretienProfessionnelByAgentAndCampagne(Agent $agent, EntretienProfessionnelCampagne $campagne)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('entretien.agent = :agent')
            ->setParameter('agent', $agent)
            ->andWhere('entretien.campagne = :campagne')
            ->setParameter('campagne', $campagne)
            ->andWhere('entretien.histoDestruction IS NULL')
            ;

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs entretiens professionnels ont été trouvés pour la campagne [".$campagne->getId()."|".$campagne->getAnnee()."] et l'agent [".$agent->getId()."|".$agent->getDenomination()."]",0,$e);
        }
        return $result;
    }

    /** FONCTIONS UTILITAIRES *****************************************************************************************/

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel
     */
    public function generateToken(EntretienProfessionnel $entretien)
    {
        try {
            $token = Uuid::uuid4();
        } catch (Exception $e) {
            throw new RuntimeException("Erreur rencontrée lors de la génération du UUID.", null, $e);
        }
        $entretien->setToken($token);
        $entretien->setAcceptation(null);
        $this->update($entretien);
        return $entretien;
    }

    public function recopiePrecedent(EntretienProfessionnel $entretien)
    {
        $previous = $this->getPreviousEntretienProfessionnel($entretien);
        if ($previous) {
            $recopies = $this->getConfigurationService()->getConfigurationsEntretienProfessionnel();
            foreach ($recopies as $recopie) {
                $splits = explode(";", $recopie->getValeur());
                $this->getFormulaireInstanceService()->recopie($previous->getFormulaireInstance(), $entretien->getFormulaireInstance(), $splits[0], $splits[1]);
            }
        }
        return $entretien;
    }
}

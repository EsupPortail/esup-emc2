<?php

namespace EntretienProfessionnel\Service\EntretienProfessionnel;

use Application\Entity\Db\Agent;
use Application\Service\Configuration\ConfigurationServiceAwareTrait;
use Application\Service\GestionEntiteHistorisationTrait;
use Autoform\Service\Formulaire\FormulaireInstanceServiceAwareTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use Exception;
use Ramsey\Uuid\Uuid;
use UnicaenApp\Exception\RuntimeException;
use Zend\Mvc\Controller\AbstractActionController;

class EntretienProfessionnelService {
    use GestionEntiteHistorisationTrait;
    use ConfigurationServiceAwareTrait;
    use FormulaireInstanceServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function create(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->createFromTrait($entretien);
        $this->generateToken($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function update(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->updateFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function historise(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->historiserFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function restore(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->restoreFromTrait($entretien);
        return $entretien;
    }

    /**
     * @return EntretienProfessionnel
     * @var EntretienProfessionnel $entretien
     */
    public function delete(EntretienProfessionnel $entretien) : EntretienProfessionnel
    {
        $this->deleteFromTrait($entretien);
        return $entretien;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
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
    public function getEntretiensProfessionnels() : array
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('campagne.annee, agent.nomUsuel, agent.prenom');
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return EntretienProfessionnel|null
     */
    public function getEntretienProfessionnel(int $id) : ?EntretienProfessionnel
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
    public function getRequestedEntretienProfessionnel(AbstractActionController $controller, $paramName = 'entretien-professionnel') : ?EntretienProfessionnel
    {
        $id = $controller->params()->fromRoute($paramName);
        $entretien = $this->getEntretienProfessionnel($id);
        return $entretien;
    }

    /**
     * @param Agent $agent
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnelsParAgent(Agent $agent) : array
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
     * @return EntretienProfessionnel|null
     */
    public function getEntretienProfessionnelByToken(string $token) : ?EntretienProfessionnel
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
     * @return EntretienProfessionnel|null
     */
    public function getPreviousEntretienProfessionnel(EntretienProfessionnel $entretien) : ?EntretienProfessionnel
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

    /**
     * @param Agent $agent
     * @param Campagne $campagne
     * @return EntretienProfessionnel|null
     */
    public function getEntretienProfessionnelByAgentAndCampagne(Agent $agent, Campagne $campagne) : ?EntretienProfessionnel
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
    public function generateToken(EntretienProfessionnel $entretien) : EntretienProfessionnel
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

    /**
     * @param EntretienProfessionnel $entretien
     * @return EntretienProfessionnel
     */
    public function recopiePrecedent(EntretienProfessionnel $entretien) : EntretienProfessionnel
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

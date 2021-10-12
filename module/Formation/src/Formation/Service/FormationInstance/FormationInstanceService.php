<?php

namespace Formation\Service\FormationInstance;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstance;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Contenu\ContenuServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceService
{
    use GestionEntiteHistorisationTrait;
    use EtatServiceAwareTrait;
    use ContenuServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function create(FormationInstance $instance)
    {
        $this->createFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function update(FormationInstance $instance)
    {
        $this->updateFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function historise(FormationInstance $instance)
    {
        $this->historiserFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function restore(FormationInstance $instance)
    {
        $this->restoreFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function delete(FormationInstance $instance)
    {
        $this->deleteFromTrait($instance);
        return $instance;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder()
    {
        $qb = $this->getEntityManager()->getRepository(FormationInstance::class)->createQueryBuilder('Finstance')
            ->addSelect('formation')->join('Finstance.formation', 'formation')
            ->addSelect('journee')->leftJoin('Finstance.journees', 'journee')
            ->addSelect('inscrit')->leftJoin('Finstance.inscrits', 'inscrit')
            ->addSelect('frais')->leftJoin('inscrit.frais', 'frais')
            ->addSelect('agent')->leftJoin('inscrit.agent', 'agent')
            ->addSelect('affectation')->leftJoin('agent.affectations', 'affectation')
            ->addSelect('structure')->leftJoin('affectation.structure', 'structure')
            ->addSelect('etat')->leftjoin('Finstance.etat', 'etat')
            ->addSelect('etype')->leftjoin('etat.type', 'etype')
        ;
        return $qb;
    }

    /**
     * @param string $champ
     * @param string $ordre
     * @return FormationInstance[]
     */
    public function getFormationsInstances($champ = 'id', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->orderBy('Finstance.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param Formation $formation
     * @param string $champ
     * @param string $ordre
     * @return array
     */
    public function getFormationsInstancesByFormation(Formation $formation, $champ = 'id', $ordre = 'ASC')
    {
        $qb = $this->createQueryBuilder()
            ->addSelect('finstance')->leftJoin('formation.instances', 'finstance')
            ->andWhere('formation.id = :id')
            ->setParameter('id', $formation->getId())
            ->orderBy('Finstance.' . $champ, $ordre);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param string $etatCode
     * @return FormationInstance[]
     */
    public function getFormationsInstancesByEtat(string $etatCode) : array
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('etat.code = :code')
            ->setParameter('code', $etatCode)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    /**
     * @param integer $id
     * @return FormationInstance
     */
    public function getFormationInstance(int $id)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('Finstance.id = :id')
            ->setParameter('id', $id);

        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstance partagent le même id [" . $id . "]", 0, $e);
        }
        return $result;
    }

    /**
     * @param AbstractActionController $controller
     * @param string $param
     * @return FormationInstance
     */
    public function getRequestedFormationInstance(AbstractActionController $controller, $param = 'formation-instance')
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationInstance($id);

        return $result;
    }

    public function getFormationInstanceBySource(string $source, string $idSource)
    {
        $qb = $this->createQueryBuilder()
            ->andWhere('Finstance.source = :source')
            ->andWhere('Finstance.idSource = :idSource')
            ->setParameter('source', $source)
            ->setParameter('idSource', $idSource)
        ;
        try {
            $result = $qb->getQuery()->getOneOrNullResult();
        } catch (NonUniqueResultException $e) {
            throw new RuntimeException("Plusieurs FormationInstance partagent le même idSource [" . $source . "-" . $idSource . "]", 0, $e);
        }
        return $result;
    }

    /** Fonctions associées aux états de l'instance *******************************************************************/

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function ouvrirInscription(FormationInstance $instance) : FormationInstance
    {
        $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_INSCRIPTION_OUVERTE));
        $this->update($instance);

        $email = $this->getParametreService()->getParametreByCode('GLOBAL', 'MAIL_LISTE_BIATS');
        $vars = [ 'formation-instance' => $instance ];
        $contenu = $this->getContenuService()->generateContenu("FORMATION_INSCRIPTION_OUVERTE", $vars);
        $mail = $this->getMailService()->sendMail($email, $contenu->getSujet(), $contenu->getCorps());
        $mail->setEntity($instance);
        $mail->setTemplateCode($contenu->getTemplate()->getCode());
        $this->getMailService()->update($mail);

        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function fermerInscription(FormationInstance $instance) : FormationInstance
    {
        $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_INSCRIPTION_FERMEE));
        $this->update($instance);
        foreach ($instance->getListePrincipale() as $inscrit) {

            $vars = [ 'formation-instance' => $instance ];
            $contenu = $this->getContenuService()->generateContenu("FORMATION_LISTE_PRINCIPALE", $vars);
            $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $contenu->getSujet(), $contenu->getCorps());
            $mail->setEntity($instance);
            $mail->setTemplateCode($contenu->getTemplate()->getCode());
            $this->getMailService()->update($mail);

        }
        foreach ($instance->getListeComplementaire() as $inscrit) {

            $vars = [ 'formation-instance' => $instance ];
            $contenu = $this->getContenuService()->generateContenu("FORMATION_LISTE_COMPLEMENTAIRE", $vars);
            $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $contenu->getSujet(), $contenu->getCorps());
            $mail->setEntity($instance);
            $mail->setTemplateCode($contenu->getTemplate()->getCode());
            $this->getMailService()->update($mail);

        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function envoyerConvocation(FormationInstance $instance) : FormationInstance
    {
        $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_FORMATION_CONVOCATION));
        $this->update($instance);
        foreach ($instance->getListePrincipale() as $inscrit) {

            $vars = ['formation' => $instance->getFormation(), 'formation-instance' => $instance, 'agent' => $inscrit->getAgent()];
            $contenu = $this->getContenuService()->generateContenu("FORMATION_CONVOCATION", $vars);
            $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $contenu->getSujet(), $contenu->getCorps());
            $mail->setEntity($instance);
            $mail->setTemplateCode($contenu->getTemplate()->getCode());
            $this->getMailService()->update($mail);
        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function envoyerEmargement(FormationInstance $instance) : FormationInstance
    {
        $this->update($instance);
        $mails = [];
        foreach ($instance->getFormateurs() as $formateur) {
            $mails[] = $formateur->getEmail();
        }

        $vars = ['formation' => $instance->getFormation(), 'formation-instance' => $instance];
        $contenu = $this->getContenuService()->generateContenu("FORMATION_EMARGEMENT", $vars);
        $mail = $this->getMailService()->sendMail(implode(",", $mails), $contenu->getSujet(), $contenu->getCorps());
        $mail->setEntity($instance);
        $mail->setTemplateCode($contenu->getTemplate()->getCode());
        $this->getMailService()->update($mail);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function demanderRetour(FormationInstance $instance) : FormationInstance
    {
        $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_ATTENTE_RETOURS));
        $this->update($instance);
        foreach ($instance->getListePrincipale() as $inscrit) {
            $vars = [ 'formation' => $instance->getFormation(), 'formation-instance' => $instance, 'agent' => $inscrit->getAgent() ];
            $contenu = $this->getContenuService()->generateContenu("FORMATION_RETOUR", $vars);
            $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $contenu->getSujet(), $contenu->getCorps());
            $mail->setEntity($instance);
            $mail->setTemplateCode($contenu->getTemplate()->getCode());
            $this->getMailService()->update($mail);
        }
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function cloturer(FormationInstance $instance) : FormationInstance
    {
        $instance->setEtat($this->getEtatService()->getEtatByCode(FormationInstance::ETAT_CLOTURE_INSTANCE));
        $this->update($instance);
        return $instance;
    }
}
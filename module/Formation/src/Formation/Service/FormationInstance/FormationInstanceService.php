<?php

namespace Formation\Service\FormationInstance;

use Application\Service\GestionEntiteHistorisationTrait;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationInstance;
use Formation\Service\Url\UrlServiceAwareTrait;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenMail\Service\Mail\MailServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;

class FormationInstanceService
{
    use GestionEntiteHistorisationTrait;
    use EtatServiceAwareTrait;
    use RenduServiceAwareTrait;
    use MailServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use UrlServiceAwareTrait;

    /** GESTION DES ENTITES *******************************************************************************************/

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function create(FormationInstance $instance) : FormationInstance
    {
        $this->createFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function update(FormationInstance $instance) : FormationInstance
    {
        $this->updateFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function historise(FormationInstance $instance) : FormationInstance
    {
        $this->historiserFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function restore(FormationInstance $instance) : FormationInstance
    {
        $this->restoreFromTrait($instance);
        return $instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstance
     */
    public function delete(FormationInstance $instance) : FormationInstance
    {
        $this->deleteFromTrait($instance);
        return $instance;
    }

    /** REQUETAGE *****************************************************************************************************/

    /**
     * @return QueryBuilder
     */
    public function createQueryBuilder() : QueryBuilder
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
    public function getFormationsInstances(string $champ = 'id', string $ordre = 'ASC') : array
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
    public function getFormationsInstancesByFormation(Formation $formation, string $champ = 'id', string $ordre = 'ASC') : array
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
     * @param int|null $id
     * @return FormationInstance|null
     */
    public function getFormationInstance(?int $id) : ?FormationInstance
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
     * @return FormationInstance|null
     */
    public function getRequestedFormationInstance(AbstractActionController $controller, string $param = 'formation-instance') : ?FormationInstance
    {
        $id = $controller->params()->fromRoute($param);
        $result = $this->getFormationInstance($id);

        return $result;
    }

    /**
     * @param string $source
     * @param string $idSource
     * @return FormationInstance|null
     */
    public function getFormationInstanceBySource(string $source, string $idSource) : ?FormationInstance
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

        $email = $this->getParametreService()->getParametreByCode('GLOBAL', 'MAIL_LISTE_BIATS')->getValeur();
        $vars = [
            'instance' => $instance,
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSCRIPTION_OUVERTE", $vars);
        $mail = $this->getMailService()->sendMail($email, $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
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

            $vars = [ 'agent' => $inscrit->getAgent(), 'instance' => $instance, 'UrlService' => $this->getUrlService() ];
            $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSTANCE_LISTE_PRINCIPALE", $vars);
            $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
            $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
            $this->getMailService()->update($mail);

        }
        foreach ($instance->getListeComplementaire() as $inscrit) {

            $vars = [ 'agent' => $inscrit->getAgent(), 'instance' => $instance, 'UrlService' => $this->getUrlService() ];
            $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSTANCE_LISTE_COMPLEMENTAIRE", $vars);
            $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
            $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
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

            $vars = ['instance' => $instance, 'agent' => $inscrit->getAgent(), 'UrlService' => $this->getUrlService()];
            $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSTANCE_CONVOCATION", $vars);
            $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
            $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
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

        $urlService = $this->getUrlService()->setVariables(['instance' => $instance]);
        $vars = ['instance' => $instance, 'UrlService' => $urlService];
        $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSTANCE_EMARGEMENT", $vars);
        $mail = $this->getMailService()->sendMail(implode(",", $mails), $rendu->getSujet(), $rendu->getCorps());
        $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
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
            $vars = [ 'instance' => $instance, 'agent' => $inscrit->getAgent(), 'UrlService' => $this->getUrlService()];
            $rendu = $this->getRenduService()->genereateRenduByTemplateCode("FORMATION_INSTANCE_RETOUR", $vars);
            $mail = $this->getMailService()->sendMail($inscrit->getAgent()->getEmail(), $rendu->getSujet(), $rendu->getCorps());
            $mail->setMotsClefs([$instance->generateTag(), $rendu->getTemplate()->generateTag()]);
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

    /**
     * @return FormationInstance[]
     * @attention se base sur l'etat !
     */
    public function getFormationInstanceEnCours() : array
    {
//        select fi.id, fi.formation_id, uee.code, f.libelle
//        from formation_instance fi
//        join formation f on fi.formation_id = f.id
//        left join unicaen_etat_etat uee on fi.etat_id = uee.id
//        where uee.id IS NOT NULL AND uee.code <> 'FORMATION_FERMEE'
        $qb = $this->createQueryBuilder()
            ->andWhere('Finstance.etat IS NOT NULL AND etat.code <> :cloturer')
            ->setParameter('cloturer', FormationInstance::ETAT_CLOTURE_INSTANCE)
        ;

        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
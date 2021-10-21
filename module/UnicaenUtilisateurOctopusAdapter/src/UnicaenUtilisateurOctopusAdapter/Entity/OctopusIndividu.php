<?php

namespace UnicaenUtilisateurOctopusAdapter\Entity;

use Octopus\Entity\Db\Individu;
use UnicaenUtilisateur\Service\RechercheIndividu\RechercheIndividuResultatInterface;

class OctopusIndividu implements RechercheIndividuResultatInterface {
    
    /** @var Individu $individu */
    private $individu;
    /** @var string */
    private $login;
    /** @var string */
    private $email;

    /**
     * @return Individu
     */
    public function getIndividu()
    {
        return $this->individu;
    }

    /**
     * @param Individu $individu
     * @return Individu
     */
    public function setIndividu($individu)
    {
        $this->individu = $individu;
        $comptes = $this->individu->getComptes();
        if (! empty($comptes)) {
            $compte = $comptes[0];
            $this->login = $compte->getLogin();
            $this->email = $compte->getEmail();
        }

        return $this->individu;
    }

    /** Fonction de l'interface RechercheIndividuResultatInterface */

    public function getId() {
        return $this->individu->getCIndividuChaine();
    }

    public function getUsername()
    {
        $login = $this->getLogin();
        if ($login !== null) return $login;
        return $this->individu->getCIndividuChaine();
    }

    public function getDisplayname()
    {
        return $this->individu->__toString();
    }

    /**
     * @return string|null
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }
}
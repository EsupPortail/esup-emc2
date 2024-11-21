<?php

namespace Carriere\Service\NiveauEnveloppe;

use Carriere\Entity\Db\NiveauEnveloppe;
use DoctrineModule\Persistence\ProvidesObjectManager;

class NiveauEnveloppeService
{

    use ProvidesObjectManager;

    /** GESTIONS DES ENTITES ******************************************************************************************/

    public function create(NiveauEnveloppe $metierNiveau): NiveauEnveloppe
    {
        $this->getObjectManager()->persist($metierNiveau);
        $this->getObjectManager()->flush($metierNiveau);
        return $metierNiveau;
    }

    public function update(NiveauEnveloppe $metierNiveau): NiveauEnveloppe
    {
        $this->getObjectManager()->flush($metierNiveau);
        return $metierNiveau;
    }

    public function historise(NiveauEnveloppe $metierNiveau): NiveauEnveloppe
    {
        $metierNiveau->historiser();
        $this->getObjectManager()->flush($metierNiveau);
        return $metierNiveau;
    }

    public function restore(NiveauEnveloppe $metierNiveau): NiveauEnveloppe
    {
        $metierNiveau->dehistoriser();
        $this->getObjectManager()->flush($metierNiveau);
        return $metierNiveau;
    }

    public function delete(NiveauEnveloppe $metierNiveau): NiveauEnveloppe
    {
        $this->getObjectManager()->remove($metierNiveau);
        $this->getObjectManager()->flush($metierNiveau);
        return $metierNiveau;
    }
}
<?php /** @noinspection PhpUnusedAliasInspection */

/** @noinspection PhpUnusedAliasInspection */

namespace Formation\Form\EnqueteReponse;

use Doctrine\Common\Collections\ArrayCollection;
use Formation\Entity\Db\EnqueteQuestion;
use Formation\Entity\Db\EnqueteReponse;
use Formation\Entity\Db\Inscription;
use Laminas\Hydrator\HydratorInterface;

class EnqueteReponseHydrator implements HydratorInterface {

    public function extract(object $object) : array
    {
        $data = [];
        foreach ($object as $item) {
            [$question, $reponse] = $item;
            if (isset($reponse)) {
                $data["select_" . $reponse->getQuestion()->getId()] = $reponse->getNiveau();
                $data["textarea_" . $reponse->getQuestion()->getId()] = $reponse->getDescription();
            }
        }
        return $data;

    }

    public function hydrate(array $data, object $object): object
    {
        foreach($object as $item) {
            [$question, $reponse] = $item;

            $select_id = "select_" . $question->getId();
            $reponse->setNiveau($data[$select_id]);
            $textarea_id = "textarea_" . $question->getId();
            $reponse->setDescription($data[$textarea_id]);
        }

        return $object;
    }


}
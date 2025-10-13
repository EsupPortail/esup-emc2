<?php

namespace Element\Form\Application;

use Element\Entity\Db\Application;
use Element\Service\ApplicationTheme\ApplicationThemeServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class ApplicationHydrator implements HydratorInterface {
    use ApplicationThemeServiceAwareTrait;

    /**
     * @param Application $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'groupe' => ($object->getTheme())?$object->getTheme()->getId():null,
            'HasDescription' => [
                'description' => $object->getDescription()
            ],
            'url' => $object->getUrl(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Application $object
     * @return Application
     */
    public function hydrate(array $data, $object) : object
    {
        $groupe = (isset($data['groupe']) AND trim($data['groupe']) !== "")?$this->getApplicationThemeService()->getApplicationTheme($data['groupe']):null;
        $object->setTheme($groupe);

        $object->setLibelle($data['libelle']);
        if ($data['url'] === null || $data['url'] === '') {
            $object->setUrl(null);
        } else {
            $object->setUrl($data['url']);
        }

        $description = (isset($data['HasDescription']) AND isset($data['HasDescription']['description']) && trim($data['HasDescription']['description']) != '')?trim($data['HasDescription']['description']):null;
        $object->setDescription($description);

        return $object;
    }

}
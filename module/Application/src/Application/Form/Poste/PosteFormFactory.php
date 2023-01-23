<?php

namespace Application\Form\Poste;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class PosteFormFactory {

    /**
     * @param ContainerInterface $container
     * @return PosteForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : PosteForm
    {
        /** @var PosteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(PosteHydrator::class);

        $form = new PosteForm();
        $form->setHydrator($hydrator);

        return $form;
    }
}

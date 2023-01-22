<?php

namespace DoctrineORMModuleTest\Assets\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="doctrine_orm_module_test")
 */
class Test
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer");
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $username;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    protected $password;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPassword(string $password): void
    {
        $this->password = (string) $password;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setUsername(string $username): void
    {
        $this->username = (string) $username;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Used for testing DoctrineEntity form element
     */
    public function __toString(): string
    {
        return (string) $this->username;
    }
}

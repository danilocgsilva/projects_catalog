<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\EnvironmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnvironmentRepository::class)]
class Environment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, DatabaseCredential>
     */
    #[ORM\OneToMany(targetEntity: DatabaseCredential::class, mappedBy: 'environment')]
    private Collection $databaseCredentials;

    public function __construct()
    {
        $this->databaseCredentials = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, DatabaseCredential>
     */
    public function getDatabaseCredentials(): Collection
    {
        return $this->databaseCredentials;
    }

    public function addDatabaseCredential(DatabaseCredential $databaseCredential): static
    {
        if (!$this->databaseCredentials->contains($databaseCredential)) {
            $this->databaseCredentials->add($databaseCredential);
            $databaseCredential->setEnvironment($this);
        }

        return $this;
    }

    public function removeDatabaseCredential(DatabaseCredential $databaseCredential): static
    {
        if ($this->databaseCredentials->removeElement($databaseCredential)) {
            // set the owning side to null (unless already changed)
            if ($databaseCredential->getEnvironment() === $this) {
                $databaseCredential->setEnvironment(null);
            }
        }

        return $this;
    }
}

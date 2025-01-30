<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, RepositoryAddress>
     */
    #[ORM\OneToMany(targetEntity: RepositoryAddress::class, mappedBy: 'project')]
    private Collection $repositoryAddresses;

    public function __construct()
    {
        $this->repositoryAddresses = new ArrayCollection();
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

    /**
     * @return Collection<int, RepositoryAddress>
     */
    public function getRepositoryAddresses(): Collection
    {
        return $this->repositoryAddresses;
    }

    public function addRepositoryAddress(RepositoryAddress $repositoryAddress): static
    {
        if (!$this->repositoryAddresses->contains($repositoryAddress)) {
            $this->repositoryAddresses->add($repositoryAddress);
            $repositoryAddress->setProject($this);
        }

        return $this;
    }

    public function removeRepositoryAddress(RepositoryAddress $repositoryAddress): static
    {
        if ($this->repositoryAddresses->removeElement($repositoryAddress)) {
            // set the owning side to null (unless already changed)
            if ($repositoryAddress->getProject() === $this) {
                $repositoryAddress->setProject(null);
            }
        }

        return $this;
    }
}

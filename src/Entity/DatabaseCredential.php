<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\DatabaseCredentialRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ambta\DoctrineEncryptBundle\Configuration\Encrypted;

#[ORM\Entity(repositoryClass: DatabaseCredentialRepository::class)]
class DatabaseCredential
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $databaseName = null;

    #[ORM\Column(nullable: true)]
    private ?int $port = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $host = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Encrypted]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $user = null;

    #[ORM\ManyToOne(inversedBy: 'databaseCredentials')]
    private ?Environment $environment = null;

    /**
     * @var Collection<int, DatabaseBackupFile>
     */
    #[ORM\OneToMany(targetEntity: DatabaseBackupFile::class, mappedBy: 'databaseCredential')]
    private Collection $databaseBackupFiles;

    public function __construct()
    {
        $this->databaseBackupFiles = new ArrayCollection();
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

    public function getDatabaseName(): ?string
    {
        return $this->databaseName;
    }

    public function setDatabaseName(?string $databaseName): static
    {
        $this->databaseName = $databaseName;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(?int $port): static
    {
        $this->port = $port;

        return $this;
    }

    public function getHost(): ?string
    {
        return $this->host;
    }

    public function setHost(?string $host): static
    {
        $this->host = $host;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function __get($property): mixed
    {
        return $this->{$property};
    }

    public function getEnvironment(): ?Environment
    {
        return $this->environment;
    }

    public function setEnvironment(?Environment $environment): static
    {
        $this->environment = $environment;

        return $this;
    }

    /**
     * @return Collection<int, DatabaseBackupFile>
     */
    public function getDatabaseBackupFiles(): Collection
    {
        return $this->databaseBackupFiles;
    }

    public function addDatabaseBackupFile(DatabaseBackupFile $databaseBackupFile): static
    {
        if (!$this->databaseBackupFiles->contains($databaseBackupFile)) {
            $this->databaseBackupFiles->add($databaseBackupFile);
            $databaseBackupFile->setDatabaseCredential($this);
        }

        return $this;
    }

    public function removeDatabaseBackupFile(DatabaseBackupFile $databaseBackupFile): static
    {
        if ($this->databaseBackupFiles->removeElement($databaseBackupFile)) {
            // set the owning side to null (unless already changed)
            if ($databaseBackupFile->getDatabaseCredential() === $this) {
                $databaseBackupFile->setDatabaseCredential(null);
            }
        }

        return $this;
    }
}

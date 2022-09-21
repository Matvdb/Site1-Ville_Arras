<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;
    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];
    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**   
        *     @ORM\OneToMany(targetEntity=Fichier::class,     mappedBy="proprietaire",orphanRemoval=true)     
        */
        private $fichiers;
    /**
     * @ORM\Column(type="boolean")
     */
    
    private $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=CarteIdentitee::class, mappedBy="proprietaire")
     */
    private $carteidentite;

    /**
     * @ORM\OneToMany(targetEntity=Identite::class, mappedBy="proprietaire")
     */
    private $identites;

    public function __construct()
    {
        $this->carteidentite = new ArrayCollection();
        $this->identites = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getEmail(): ?string
    {
        return $this->email;
    }
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }
    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }
    /**     
     * @return Collection|Fichier[]     
     */    
    public function getFichiers(): Collection    
    {        
        return $this->fichiers;    
    }
    public function addFichier(Fichier $fichier): self    
    {        
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers[] = $fichier; $fichier->setProprietaire($this);        
        }        
        return $this;    
    }    
    public function removeFichier(Fichier $fichier): self    
    {        
        if ($this->fichiers->removeElement($fichier)) {
          // set the owning side to null (unless already changed)            
          if ($fichier->getProprietaire() === $this) {
          $fichier->setProprietaire(null);            
        }        
    }        
    return $this;    
}

    /**
     * @return Collection<int, CarteIdentitee>
     */
    public function getCarteidentite(): Collection
    {
        return $this->carteidentite;
    }

    public function addCarteidentite(CarteIdentitee $carteidentite): self
    {
        if (!$this->carteidentite->contains($carteidentite)) {
            $this->carteidentite[] = $carteidentite;
            $carteidentite->setProprietaire($this);
        }

        return $this;
    }

    public function removeCarteidentite(CarteIdentitee $carteidentite): self
    {
        if ($this->carteidentite->removeElement($carteidentite)) {
            // set the owning side to null (unless already changed)
            if ($carteidentite->getProprietaire() === $this) {
                $carteidentite->setProprietaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Identite>
     */
    public function getIdentites(): Collection
    {
        return $this->identites;
    }

    public function addIdentite(Identite $identite): self
    {
        if (!$this->identites->contains($identite)) {
            $this->identites[] = $identite;
            $identite->setProprietaire($this);
        }

        return $this;
    }

    public function removeIdentite(Identite $identite): self
    {
        if ($this->identites->removeElement($identite)) {
            // set the owning side to null (unless already changed)
            if ($identite->getProprietaire() === $this) {
                $identite->setProprietaire(null);
            }
        }

        return $this;
    }
}

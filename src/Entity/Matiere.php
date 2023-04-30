<?php

namespace App\Entity;

use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[ASSERT\NotBlank]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    #[ASSERT\NotBlank]
    private ?string $reference = null;

    #[ORM\ManyToMany(targetEntity: Professeur::class, mappedBy: 'matieres')]
    private Collection $professeurs;

    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: Cours::class, orphanRemoval: true)]
    private Collection $cours;

    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: AvisMatiere::class, orphanRemoval: true)]
    private Collection $avisMatieres;

    public function __construct()
    {
        $this->professeurs = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->avisMatieres = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s (%s)', $this->titre, $this->reference);
    }

    public function jsonSerialize(): mixed 
    {
        return [
            'id' => $this->getId(),
            'titre' => $this->getTitre(),
            'reference' => $this->getReference(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;
        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return Collection<int, Professeur>
     */
    public function getProfesseurs(): Collection
    {
        return $this->professeurs;
    }

    public function getFirstProfesseur(): Collection
    {
        return $this->professeurs->get(0);
    }

    public function addProfesseur(Professeur $professeur): self
    {
        if (!$this->professeurs->contains($professeur)) 
        {
            $this->professeurs->add($professeur);
            $professeur->addMatiere($this);
        }
        return $this;
    }

    public function removeProfesseur(Professeur $professeur): self
    {
        if ($this->professeurs->removeElement($professeur)) 
        {
            $professeur->removeMatiere($this);
        }
        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): self
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setMatiere($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getMatiere() === $this) {
                $cour->setMatiere(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, AvisMatiere>
     */
    public function getAvisMatieres(): Collection
    {
        return $this->avisMatieres;
    }

    public function addAvisMatiere(AvisMatiere $avisMatiere): self
    {
        if (!$this->avisMatieres->contains($avisMatiere)) {
            $this->avisMatieres->add($avisMatiere);
            $avisMatiere->setMatiere($this);
        }

        return $this;
    }

    public function removeAvisMatiere(AvisMatiere $avisMatiere): self
    {
        if ($this->avisMatieres->removeElement($avisMatiere)) {
            // set the owning side to null (unless already changed)
            if ($avisMatiere->getMatiere() === $this) {
                $avisMatiere->setMatiere(null);
            }
        }

        return $this;
    }
}

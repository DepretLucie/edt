<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as ASSERT;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
#[UniqueEntity(fields: ['numero'], errorPath: "numero", message: "Ce numéro de salle a déjà été renseigné.")]
class Salle implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    #[ASSERT\NotBlank]
    #[ASSERT\Length(min: 3, max: 5, minMessage: 'Votre salle doit faire minimum {{ limit }} caractères', maxMessage: 'Votre salle doit faire au maximum {{ limit }} caractères',)]
    #[Assert\Regex(pattern: '/S\.[0-9][0-9]?[0-9]?/', message: 'Votre salle {{ value }} doit correspondre au schéma S.XXX',)]
    private ?string $numero = null;

    #[ORM\OneToMany(mappedBy: 'salle', targetEntity: Cours::class, orphanRemoval: true)]
    private Collection $cours;

    public function __construct()
    {
        $this->cours = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s', $this->numero);
    }

    public function jsonSerialize(): mixed 
    {
        return [
            'id' => $this->getId(),
            'numero' => $this->getNumero(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

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
            $cour->setSalle($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): self
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getSalle() === $this) {
                $cour->setSalle(null);
            }
        }

        return $this;
    }
}

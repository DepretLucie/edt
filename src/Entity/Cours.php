<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as ASSERT;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[ASSERT\GreaterThan('now +1 hours')]
    #[ASSERT\Expression('this.verificationHoraire("HeureDebut","08:00", "17:00")', message:'Le début du cours doit être compris entre 08:00 et 17:00')]
    #[ASSERT\Expression('this.verificationMinutes("HeureDebut")', message:'Le cours doit commencer a pile (ex: 09:00) ou à la demi (ex: 15h30)')]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[ASSERT\GreaterThan('now +1 hours')]
    #[ASSERT\Expression('this.getDateHeureDebut() < this.getDateHeureFin()', message:'La date de fin ne doit pas être antérieur à la date de début')]
    #[ASSERT\Expression('this.verificationHoraire("HeureFin","09:00", "18:00")', message:'La fin du cours doit être comprise entre 09:00 et 18:00')]
    #[ASSERT\Expression('this.getDateHeureDebut().format("!Y-m-d") == this.getDateHeureFin().format("!Y-m-d")', message:'Le cours doit se situer dans la même journée')]
    #[ASSERT\Expression('this.verificationMinutes("HeureFin")', message:'Le cours doit terminer a pile (ex: 09:00) ou à la demi (ex: 15h30)')]
    private ?\DateTimeInterface $dateHeureFin = null;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    #[ASSERT\Expression('this.verificationProfesseurCours()', message:'Ce professeur a déjà un cours prévu à ces horaires')]
    private ?Professeur $professeur = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    #[ASSERT\Expression('this.verificationMatieresProfesseur()', message:'Cette matière n\'est pas enseignée par ce professeur')]
    private ?Matiere $matiere = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    #[ASSERT\Expression('this.verificationCoursExistant()', message:'Un cours a déjà lieu dans cette salle aux horaires indiquées')]
    private ?Salle $salle = null;

    #[ORM\OneToMany(mappedBy: 'cours', targetEntity: Note::class, orphanRemoval: true)]
    private Collection $notes;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function __toString()
    {
        return sprintf('%s %s %s %s', $this->getProfesseur(),$this->getType(),$this->getMatiere(),$this->getSalle());
    }

    public function jsonSerialize(): mixed 
    {
        return [
            'id' => $this->getId(),
            'dateHeureDebut' => $this->getDateHeureDebut(),
            'dateHeureFin' => $this->getDateHeureFin(),
            'type' => $this->getType(),
            'professeur' => $this->getProfesseur(),
            'matiere' => $this->getMatiere(),
            'salle' => $this->getSalle(),
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDateHeureFin(): ?\DateTimeInterface
    {
        return $this->dateHeureFin;
    }

    public function setDateHeureFin(\DateTimeInterface $dateHeureFin): self
    {
        $this->dateHeureFin = $dateHeureFin;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getProfesseur(): ?Professeur
    {
        return $this->professeur;
    }

    public function setProfesseur(?Professeur $professeur): self
    {
        $this->professeur = $professeur;

        return $this;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        $this->matiere = $matiere;

        return $this;
    }

    public function getSalle(): ?Salle
    {
        return $this->salle;
    }

    public function setSalle(?Salle $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    public function verificationHoraire(string $heure, string $heure_debut, string $heure_fin): bool
    {
        if($heure == "HeureDebut")
        {
            return $this->getDateHeureDebut()->format("H:i") >= \DateTime::createFromFormat("H:i", $heure_debut)->format("H:i") && $this->getDateHeureDebut()->format("H:i") <= \DateTime::createFromFormat("H:i", $heure_fin)->format("H:i");
        }
        else
        {
            return $this->getDateHeureFin()->format("H:i") >= \DateTime::createFromFormat("H:i", $heure_debut)->format("H:i") && $this->getDateHeureFin()->format("H:i") <= \DateTime::createFromFormat("H:i", $heure_fin)->format("H:i");
        }
    }

    public function verificationCoursExistant(): bool
    {
        $listeCours = $this->salle->getCours();
        foreach($listeCours as $cours){
            if(
                $this->getDateHeureDebut() >= $cours->getDateHeureDebut() &&
                $this->getDateHeureDebut() <= $cours->getDateHeureFin() ||
                $this->getDateHeureFin() >= $cours->getDateHeureDebut() &&
                $this->getDateHeureFin() <= $cours->getDateHeureFin()
            )
            {
                return false;
            }
        }
        return true;
    }

    public function verificationProfesseurCours(): bool
    {
        $listeCours = $this->professeur->getCours();
        foreach($listeCours as $cours){
            if(
                $this->getDateHeureDebut() >= $cours->getDateHeureDebut() &&
                $this->getDateHeureDebut() <= $cours->getDateHeureFin() ||
                $this->getDateHeureFin() >= $cours->getDateHeureDebut() &&
                $this->getDateHeureFin() <= $cours->getDateHeureFin()
            )
            {
                return false;
            }
        }
        return true;
    }

    public function verificationMatieresProfesseur(): bool
    {
        $listeProfesseurs = $this->matiere->getProfesseurs();
        foreach($listeProfesseurs as $professeur){
            if($professeur == $this->professeur)
            {
                return true;
            }
        }
        return false;
    }

    public function verificationMinutes(string $heure): bool 
    {
        if($heure == "HeureDebut")
        {
            return $this->getDateHeureDebut()->format("i") == \DateTime::createFromFormat("i", "00")->format("i") || $this->getDateHeureDebut()->format("i") == \DateTime::createFromFormat("i", "30")->format("i");
        }
        else
        {
            return $this->getDateHeureFin()->format("i") == \DateTime::createFromFormat("i", "00")->format("i") || $this->getDateHeureFin()->format("i") == \DateTime::createFromFormat("i", "30")->format("i");
        }
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setCours($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getCours() === $this) {
                $note->setCours(null);
            }
        }

        return $this;
    }
}
?>
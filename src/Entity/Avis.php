<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use App\Entity\Professeur;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as ASSERT;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[UniqueEntity(fields: ['professeur', 'mail_etudiant'], errorPath: "mail_etudiant", message: "Cet étudiant a déjà noté ce professeur.")]
class Avis implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[ASSERT\NotBlank]
    #[ASSERT\Range(min: 0, max: 5)]
    private ?int $note = null;

    #[ORM\Column(type: Types::TEXT)]
    #[ASSERT\NotBlank]
    private ?string $commentaire = null;

    #[ORM\Column(length: 255)]
    #[ASSERT\NotBlank]
    #[ASSERT\Email]
    private ?string $mail_etudiant = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Professeur $professeur = null;

    public function jsonSerialize(): mixed 
    {
        return [
            'id' => $this->getId(),
            'note' => $this->getNote(),
            'commentaire' => $this->getCommentaire(),
            'mail_etudiant' => $this->getMailEtudiant(),
        ];
    }

    public function fromArray(array $data): self
    {
        $this->note = $data['note'] ?? $this->note;
        $this->commentaire = $data['commentaire'] ?? $this->commentaire;
        $this->mail_etudiant = $data['mail_etudiant'] ?? $this->mail_etudiant;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getMailEtudiant(): ?string
    {
        return $this->mail_etudiant;
    }

    public function setMailEtudiant(string $mail_etudiant): self
    {
        $this->mail_etudiant = $mail_etudiant;
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
}

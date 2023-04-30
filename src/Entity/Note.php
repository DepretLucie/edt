<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Cours;
use Symfony\Component\Validator\Constraints as ASSERT;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
#[UniqueEntity(fields: ['cours', 'mail_etudiant'], errorPath: "mail_etudiant", message: "Cet étudiant a déjà noté ce cours.")]
class Note implements \JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[ASSERT\NotBlank]
    #[ASSERT\Range(min: 0, max: 5)]
    private ?int $note = null;

    #[ORM\Column(length: 255)]
    #[ASSERT\NotBlank]
    #[ASSERT\Email]
    private ?string $mail_etudiant = null;

    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cours $cours = null;

    public function jsonSerialize(): mixed 
    {
        return [
            'id' => $this->getId(),
            'note' => $this->getNote(),
            'mail_etudiant' => $this->getMailEtudiant(),
        ];
    }

    public function fromArray(array $data): self
    {
        $this->note = $data['note'] ?? $this->note;
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

    public function getMailEtudiant(): ?string
    {
        return $this->mail_etudiant;
    }

    public function setMailEtudiant(string $mail_etudiant): self
    {
        $this->mail_etudiant = $mail_etudiant;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): self
    {
        $this->cours = $cours;

        return $this;
    }
}

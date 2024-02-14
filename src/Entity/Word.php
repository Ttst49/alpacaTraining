<?php

namespace App\Entity;

use App\Repository\WordRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WordRepository::class)]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $value = null;

    #[ORM\OneToMany(targetEntity: Embedding::class, mappedBy: 'word', orphanRemoval: true)]
    private Collection $embeddings;

    public function __construct()
    {
        $this->embeddings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): static
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return Collection<int, Embedding>
     */
    public function getEmbeddings(): Collection
    {
        return $this->embeddings;
    }

    public function addEmbedding(Embedding $embedding): static
    {
        if (!$this->embeddings->contains($embedding)) {
            $this->embeddings->add($embedding);
            $embedding->setWord($this);
        }

        return $this;
    }

    public function removeEmbedding(Embedding $embedding): static
    {
        if ($this->embeddings->removeElement($embedding)) {
            // set the owning side to null (unless already changed)
            if ($embedding->getWord() === $this) {
                $embedding->setWord(null);
            }
        }

        return $this;
    }
}

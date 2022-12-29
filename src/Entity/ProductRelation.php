<?php

namespace App\Entity;

use App\Repository\ProductsRelationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRelationRepository::class)]
class ProductRelation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $source = null;

    #[ORM\Column]
    private ?int $target = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSource(): ?int
    {
        return $this->source;
    }

    public function setSource(int $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getTarget(): ?int
    {
        return $this->target;
    }

    public function setTarget(int $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
}

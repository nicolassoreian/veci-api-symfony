<?php

namespace App\Entity;

use App\Repository\StoreScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StoreScheduleRepository::class)]
#[UniqueEntity('weekDay', 'store')]
#[Index(name: 'search_idx', columns: ['store_id'])]
class StoreSchedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['stores:read'])]
    private ?string $weekDay = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['stores:read'])]
    private ?\DateTimeInterface $timeFrom = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    #[Groups(['stores:read'])]
    private ?\DateTimeInterface $timeTo = null;

    #[ORM\ManyToOne(inversedBy: 'schedule')]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    private ?Store $store = null;

    #[ORM\Column]
    #[Groups(['stores:read'])]
    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    private ?bool $isOpen = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeekDay(): ?string
    {
        return $this->weekDay;
    }

    public function setWeekDay(string $weekDay): static
    {
        $this->weekDay = $weekDay;

        return $this;
    }

    public function getTimeFrom(): ?string
    {
        if ($this->timeFrom) {
            return $this->timeFrom->format('H:i:s');
        }

        return $this->timeFrom;
    }

    public function setTimeFrom(?\DateTimeInterface $timeFrom): static
    {
        $this->timeFrom = $timeFrom;

        return $this;
    }

    public function getTimeTo(): ?string
    {
        if ($this->timeTo) {
            return $this->timeTo->format('H:i:s');
        }

        return $this->timeTo;
    }

    public function setTimeTo(?\DateTimeInterface $timeTo): static
    {
        $this->timeTo = $timeTo;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): static
    {
        $this->store = $store;

        return $this;
    }

    public function isOpen(): ?bool
    {
        return $this->isOpen;
    }

    public function setOpen(bool $isOpen): static
    {
        $this->isOpen = $isOpen;

        return $this;
    }
}

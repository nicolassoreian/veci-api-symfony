<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GraphQl\DeleteMutation;
use ApiPlatform\Metadata\GraphQl\Mutation;
use App\Repository\StoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use LongitudeOne\Spatial\PHP\Types\SpatialInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\GraphQl\Query;
use ApiPlatform\Metadata\GraphQl\QueryCollection;

#[ORM\Entity(repositoryClass: StoreRepository::class)]

#[ApiResource(
    normalizationContext: ['groups' => ['stores:read']],
    paginationClientEnabled: true,
    graphQlOperations: [
        new Query(),
        new QueryCollection(),
        new Mutation(name: 'create'),
        new Mutation(name: 'update'),
        new DeleteMutation(name: 'delete'),
    ]
)]
class Store
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['stores:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['stores:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Groups(['stores:read'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?bool $enabled = null;

    #[ORM\ManyToOne(inversedBy: 'stores')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['stores:read'])]
    private ?Brand $brand = null;

    #[ORM\Column(length: 255)]
    #[Groups(['stores:read'])]
    private ?string $address = null;

    /**
     * @var Collection<int, StoreProduct>
     */
    #[ORM\OneToMany(targetEntity: StoreProduct::class, mappedBy: 'store')]
    #[Groups(['stores:read'])]
    private Collection $products;

    #[ORM\Column(type: 'point', nullable: true)]
    #[Assert\NotBlank]
    #[Groups(['stores:read'])]
    private ?SpatialInterface $geolocation = null;

    /**
     * @var Collection<int, StoreSchedule>
     */
    #[ORM\OneToMany(targetEntity: StoreSchedule::class, mappedBy: 'store')]
    #[Assert\NotBlank]
    #[Groups(['stores:read'])]
    private Collection $schedule;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->schedule = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    /**
     * @return Collection<int, StoreProduct>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(StoreProduct $product): static
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setStore($this);
        }

        return $this;
    }

    public function removeProduct(StoreProduct $product): static
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getStore() === $this) {
                $product->setStore(null);
            }
        }

        return $this;
    }

    public function getGeolocation(): ?SpatialInterface
    {
        return $this->geolocation;
    }

    public function setGeolocation(SpatialInterface $geolocation): static
    {
        $this->geolocation = $geolocation;

        return $this;
    }

    /**
     * @return Collection<int, StoreSchedule>
     */
    public function getSchedule(): Collection
    {
        return $this->schedule;
    }

    public function addSchedule(StoreSchedule $schedule): static
    {
        if (!$this->schedule->contains($schedule)) {
            $this->schedule->add($schedule);
            $schedule->setStore($this);
        }

        return $this;
    }

    public function removeSchedule(StoreSchedule $schedule): static
    {
        if ($this->schedule->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getStore() === $this) {
                $schedule->setStore(null);
            }
        }

        return $this;
    }
}

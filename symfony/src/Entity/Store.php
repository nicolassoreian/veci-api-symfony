<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use LongitudeOne\Spatial\PHP\Types\SpatialInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: StoreRepository::class)]
// #[ApiResource(normalizationContext: ['groups' => ['stores:read']], security: "is_granted('ROLE_ADMIN')")]
#[ApiResource(normalizationContext: ['groups' => ['stores:read']], paginationClientEnabled: true, order: ['id' => 'DESC'], security: "is_granted('ROLE_ADMIN')")]
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

    public function __construct()
    {
        $this->products = new ArrayCollection();
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

    public function isEnabled(): ?bool
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
}

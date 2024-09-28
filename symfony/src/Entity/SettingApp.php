<?php

namespace App\Entity;

use App\Repository\SettingAppRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity(repositoryClass: SettingAppRepository::class)]
#[Index(name: 'search_idx', columns: ['id'])]
class SettingApp
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $siteUrl = null;

    #[ORM\Column(length: 255)]
    private ?string $siteName = null;

    #[ORM\Column(length: 255)]
    private ?string $siteShortName = null;

    #[ORM\Column(length: 255)]
    private ?string $siteSlogan = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $whatsappNumber = null;

    #[ORM\Column(nullable: true)]
    private ?bool $whatsappEnabled = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hiringEmail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $locationLink = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $legalText = null;

    #[ORM\OneToMany(targetEntity: SettingSocialNetwork::class, mappedBy: 'settingApp', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $socialNetworks;

    public function __construct()
    {
        $this->socialNetworks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiteUrl(): ?string
    {
        return $this->siteUrl;
    }

    public function setSiteUrl(string $siteUrl): static
    {
        $this->siteUrl = $siteUrl;

        return $this;
    }

    public function getSiteName(): ?string
    {
        return $this->siteName;
    }

    public function setSiteName(string $siteName): static
    {
        $this->siteName = $siteName;

        return $this;
    }

    public function getSiteShortName(): ?string
    {
        return $this->siteShortName;
    }

    public function setSiteShortName(string $siteShortName): static
    {
        $this->siteShortName = $siteShortName;

        return $this;
    }

    public function getSiteSlogan(): ?string
    {
        return $this->siteSlogan;
    }

    public function setSiteSlogan(string $siteSlogan): static
    {
        $this->siteSlogan = $siteSlogan;

        return $this;
    }

    public function getWhatsappNumber(): ?string
    {
        return $this->whatsappNumber;
    }

    public function setWhatsappNumber(?string $whatsappNumber): static
    {
        $this->whatsappNumber = $whatsappNumber;

        return $this;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): static
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    public function getHiringEmail(): ?string
    {
        return $this->hiringEmail;
    }

    public function setHiringEmail(?string $hiringEmail): static
    {
        $this->hiringEmail = $hiringEmail;

        return $this;
    }

    public function isWhatsappEnabled(): ?bool
    {
        return $this->whatsappEnabled;
    }

    public function setWhatsappEnabled(?bool $whatsappEnabled): static
    {
        $this->whatsappEnabled = $whatsappEnabled;

        return $this;
    }

    public function getLocationLink(): ?string
    {
        return $this->locationLink;
    }

    public function setLocationLink(?string $locationLink): static
    {
        $this->locationLink = $locationLink;

        return $this;
    }

    public function getLegalText(): ?string
    {
        return $this->legalText;
    }

    public function setLegalText(?string $legalText): static
    {
        $this->legalText = $legalText;

        return $this;
    }

    /**
     * @return Collection<int, SettingSocialNetwork>
     */
    public function getSocialNetworks(): Collection
    {
        return $this->socialNetworks;
    }

    public function addSocialNetwork(SettingSocialNetwork $socialNetwork): static
    {
        if (!$this->socialNetworks->contains($socialNetwork)) {
            $this->socialNetworks->add($socialNetwork);
            $socialNetwork->setSettingApp($this);
        }

        return $this;
    }

    public function removeSocialNetwork(SettingSocialNetwork $socialNetwork): static
    {
        if ($this->socialNetworks->removeElement($socialNetwork)) {
            // set the owning side to null (unless already changed)
            if ($socialNetwork->getSettingApp() === $this) {
                $socialNetwork->setSettingApp(null);
            }
        }

        return $this;
    }
}

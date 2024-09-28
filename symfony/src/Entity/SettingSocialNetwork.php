<?php

namespace App\Entity;

use App\Repository\SettingSocialNetworkRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping\Index;

#[ORM\Entity(repositoryClass: SettingSocialNetworkRepository::class)]
#[Index(name: 'search_idx', columns: ['title','enabled'])]
class SettingSocialNetwork
{
    public const SOCIAL_ICONS = [
        'Behance' => 'bi bi-behance',
        'Discord' => 'bi bi-discord',
        'Facebook' => 'bi bi-facebook',
        'Instagram' => 'bi bi-instagram filled',
        'Line' => 'bi bi-line',
        'LinkedIn' => 'bi bi-linkedin',
        'Mastodon' => 'bi bi-mastodon',
        'Medium' => 'bi bi-medium',
        'Messenger' => 'bi bi-messenger',
        'Pinterest' => 'bi bi-pinterest',
        'Reddit' => 'bi bi-reddit',
        'Skype' => 'bi bi-skype',
        'Slack' => 'bi bi-slack',
        'Snapchat' => 'bi bi-snapchat',
        'Spotify' => 'bi bi-spotify',
        'Telegram' => 'bi bi-telegram',
        'Threads' => 'bi bi-threads',
        'TikTok' => 'bi bi-tiktok',
        'Twitch' => 'bi bi-twitch',
        'Twitter X' => 'bi bi-twitter-x filled circle',
        'Vimeo' => 'bi bi-vimeo',
        'Wechat' => 'bi bi-wechat',
        'Whatsapp' => 'bi bi-whatsapp',
        'Yelp' => 'bi bi-yelp',
        'YouTube' => 'bi bi-youtube'
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    private ?string $icon = null;

    #[ORM\Column]
    private ?bool $enabled = true;

    #[ORM\ManyToOne(inversedBy: 'socialNetworks')]
    private ?SettingApp $settingApp = null;

    #[ORM\Column]
    #[Gedmo\SortablePosition]
    private ?int $position = null;

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

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): static
    {
        $this->icon = $icon;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getSettingApp(): ?SettingApp
    {
        return $this->settingApp;
    }

    public function setSettingApp(?SettingApp $settingApp): static
    {
        $this->settingApp = $settingApp;

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }
}

<?php

namespace App\Twig;

use App\Entity\SettingApp;
use App\Repository\SettingAppRepository;

class AppSettings
{
    public function __construct(
        private SettingAppRepository $settingAppRepository,
    ){
    }

    public function getProp(): array
    {
      $settings = $this->settingAppRepository->getSetting();

      return [
        'siteName' => $settings->getSiteName(),
        'siteUrl' => $settings->getSiteUrl(),
        'siteShortName' => $settings->getSiteShortName(),
        'siteSlogan' => $settings->getSiteSlogan(),
        'legalText' => $settings->getLegalText(),
        'contactEmail' => $settings->getContactEmail(),
        'hiringEmail' => $settings->getHiringEmail(),
        'phone' => $settings->getWhatsappNumber(),
        'locationLink' => $settings->getLocationLink()
      ];
    }
}
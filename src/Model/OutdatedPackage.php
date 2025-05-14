<?php

namespace RecursiveTree\Seat\PackageNotifications\Model;

class OutdatedPackage
{
    public readonly string $packageName;
    public readonly string $installedVersion;
    public readonly string $latestVersion;

    /**
     * @param string $packageName
     * @param string $installedVersion
     * @param string $latestVersion
     */
    public function __construct(string $packageName, string $installedVersion, string $latestVersion)
    {
        $this->packageName = $packageName;
        $this->installedVersion = $installedVersion;
        $this->latestVersion = $latestVersion;
    }
}
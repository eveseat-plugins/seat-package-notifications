<?php

namespace RecursiveTree\Seat\PackageNotifications\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Seat\Services\AbstractSeatPlugin;
use Seat\Services\Traits\VersionsManagementTrait;

class CheckVersions implements ShouldQueue
{
    use Dispatchable, VersionsManagementTrait;
    public function handle(): void
    {
        $plugins = $this->getPluginsMetadataList();
        foreach ($plugins->core as $package){
            $this->checkPackage($package);
        }
        foreach ($plugins->plugins as $package){
            $this->checkPackage($package);
        }
    }

    private function checkPackage($package): void
    {
        $serviceProvider = app()->getProvider($package);
        if(!$serviceProvider instanceof AbstractSeatPlugin) {
            logger()->error(sprintf("seat-package-notifications: got a service provider that is not a seat plugin: %s",$serviceProvider::class));
            return;
        }

        $installed_version = $serviceProvider->getVersion();
        // if($installed_version === "missing") return; // the plugin was installed via dev override

        $latest_version = $this->getPackageLatestVersion($serviceProvider->getPackagistVendorName(), $serviceProvider->getPackagistPackageName());
        if($installed_version !== $latest_version){
            $this->dispatchNotification($serviceProvider);
        }
    }

    private function dispatchNotification(AbstractSeatPlugin $package): void {
        //dd($package->getPackagistPackageName());
    }
}
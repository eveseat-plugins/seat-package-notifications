<?php

namespace RecursiveTree\Seat\PackageNotifications\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use RecursiveTree\Seat\PackageNotifications\Model\OutdatedPackage;
use RecursiveTree\Seat\PackageNotifications\Notifications\DiscordOutdatedPackageNotification;
use Seat\Notifications\Models\NotificationGroup;
use Seat\Notifications\Traits\NotificationDispatchTool;
use Seat\Services\AbstractSeatPlugin;
use Seat\Services\Traits\VersionsManagementTrait;

class CheckVersions implements ShouldQueue
{
    use Dispatchable, VersionsManagementTrait, NotificationDispatchTool;
    public function handle(): void
    {
        $outdatedPackages = collect();

        $plugins = $this->getPluginsMetadataList();
        foreach ($plugins->core as $package){
            $this->checkPackage($package, $outdatedPackages);
        }
        foreach ($plugins->plugins as $package){
            $this->checkPackage($package, $outdatedPackages);
        }

        if($outdatedPackages->isNotEmpty()){
            $groups = NotificationGroup::with('alerts')
                ->whereHas('alerts', function ($query) {
                    $query->where('alert', 'outdated_packages');
                })->get();
            $this->dispatchNotifications('outdated_packages', $groups, function ($notificationClass) use ($outdatedPackages) {
                return new $notificationClass($outdatedPackages);
            });
        }
    }

    private function checkPackage($package, $outdatedPackages): void
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
            $packageInfo = new OutdatedPackage(sprintf("%s/%s",$serviceProvider->getPackagistVendorName(), $serviceProvider->getPackagistPackageName()),$installed_version, $latest_version);
            $outdatedPackages->push($packageInfo);
        }
    }
}
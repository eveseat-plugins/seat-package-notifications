<?php

namespace RecursiveTree\Seat\PackageNotifications\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Collection;
use RecursiveTree\Seat\PackageNotifications\Model\OutdatedPackage;
use Seat\Notifications\Notifications\AbstractMailNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

class MailOutdatedPackageNotification extends AbstractMailNotification
{
    /**
     * @var Collection<OutdatedPackage>
     */
    private Collection $packages;

    /**
     * @param Collection<OutdatedPackage> $packages
     */
    public function __construct(Collection $packages)
    {
        $this->packages = $packages;
    }

    /**
     * Populate the content of the notification.
     *
     * @param DiscordMessage $message
     * @param  $notifiable
     * */
    protected function populateMessage(MailMessage $message, $notifiable)
    {
        $message
            ->subject("Outdated SeAT packages or plugin")
            ->line("The following packages are outdated:");

        foreach($this->packages as $package){
            $message->line(sprintf("%s: %s -> %s",$package->packageName, $package->installedVersion, $package->latestVersion));
        }
    }
}
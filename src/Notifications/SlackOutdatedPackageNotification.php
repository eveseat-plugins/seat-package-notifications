<?php

namespace RecursiveTree\Seat\PackageNotifications\Notifications;

use Illuminate\Support\Collection;
use RecursiveTree\Seat\PackageNotifications\Model\OutdatedPackage;
use Seat\Notifications\Notifications\AbstractSlackNotification;
use Illuminate\Notifications\Messages\SlackMessage;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

class SlackOutdatedPackageNotification extends AbstractSlackNotification
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
    protected function populateMessage(SlackMessage $message, $notifiable)
    {
        $message
            ->from("SeAT Package Watcher")
            ->attachment(function ($attachment) {
                $attachment
                    ->timestamp(now())
                    ->title("Outdated package detected!");

                foreach ($this->packages as $package){
                    $attachment->field(function ($field) use ($package) {
                        $field
                            ->title($package->packageName)
                            ->content(sprintf("%s -> %s", $package->installedVersion, $package->latestVersion))
                            ->long();
                    });
                }
            });
    }
}
<?php

namespace RecursiveTree\Seat\PackageNotifications\Notifications;

use Illuminate\Support\Collection;
use RecursiveTree\Seat\PackageNotifications\Model\OutdatedPackage;
use Seat\Notifications\Notifications\AbstractDiscordNotification;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbed;
use Seat\Notifications\Services\Discord\Messages\DiscordEmbedField;
use Seat\Notifications\Services\Discord\Messages\DiscordMessage;

class DiscordOutdatedPackageNotification extends AbstractDiscordNotification
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
    protected function populateMessage(DiscordMessage $message, $notifiable)
    {
        $message->embed(function (DiscordEmbed $embed){
            $embed->title("Outdated package detected!");

            foreach ($this->packages as $package){
                $embed->field(function (DiscordEmbedField $field) use ($package) {
                    $field->long();
                    $field->name($package->packageName);
                    $field->value(sprintf("%s -> %s", $package->installedVersion, $package->latestVersion));
                });
            }

            $embed->timestamp(now());
            $embed->color(DiscordMessage::WARNING);
        });
    }
}
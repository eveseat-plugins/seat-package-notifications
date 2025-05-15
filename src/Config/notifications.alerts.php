<?php

return [
    'outdated_packages' => [
        'label' => 'package-notifications::notifications.outdated_packages',
        'handlers' => [
            'discord' => \RecursiveTree\Seat\PackageNotifications\Notifications\DiscordOutdatedPackageNotification::class,
            'slack' => \RecursiveTree\Seat\PackageNotifications\Notifications\SlackOutdatedPackageNotification::class
        ],
    ]
];
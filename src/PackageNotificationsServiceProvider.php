<?php
/*
This file is part of SeAT

Copyright (C) 2015 to 2020  Leon Jacobs

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/

namespace RecursiveTree\Seat\PackageNotifications;

use RecursiveTree\Seat\PackageNotifications\Commands\CheckVersions;
use RecursiveTree\Seat\PackageNotifications\Database\Seeders\ScheduleSeeder;
use Seat\Services\AbstractSeatPlugin;

/**
 *
 */
class PackageNotificationsServiceProvider extends AbstractSeatPlugin
{
    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'package-notifications');

        $this->commands([
            CheckVersions::class
        ]);
    }

    public function register()
    {
        $this->registerDatabaseSeeders(ScheduleSeeder::class);
    }

    /**
     * Return the plugin public name as it should be displayed into settings.
     *
     * @return string
     * @example SeAT Web
     *
     */
    public function getName(): string
    {
        return 'Your Package Friendly Name';
    }

    /**
     * Return the plugin repository address.
     *
     * @example https://github.com/eveseat/web
     *
     * @return string
     */
    public function getPackageRepositoryUrl(): string
    {
        return 'https://github.com/eveseat-plugins/seat-package-notifications';
    }

    /**
     * Return the plugin technical name as published on package manager.
     *
     * @return string
     * @example web
     *
     */
    public function getPackagistPackageName(): string
    {
        return 'seat-package-notifications';
    }

    /**
     * Return the plugin vendor tag as published on package manager.
     *
     * @return string
     * @example eveseat
     *
     */
    public function getPackagistVendorName(): string
    {
        return 'recursivetree';
    }
}

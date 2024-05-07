<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Screen\Actions\Menu;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Stats')
                ->icon('bs.bar-chart')
                ->title('Navigation')
                ->route('platform.example.charts'),

            Menu::make('Orders')
                ->icon('bi.list-ul')
                ->route('platform.screens.orders'),

            Menu::make('Car classes')
                ->icon('bi.list-ul')
                ->route('platform.screens.car_classes'),

            Menu::make('Taxi')
                ->icon('bi.list-ul')
                ->route('platform.screens.taxi'),

            Menu::make('Driver')
                ->icon('bi.list-ul')
                ->route('platform.screens.driver'),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
            ItemPermission::group(__('Screens'))
                ->addPermission('platform.screen.car_classes', __('Car classes'))
                ->addPermission('platform.screen.taxi', __('Taxi'))
                ->addPermission('platform.screen.driver', __('Driver')),
        ];
    }
}

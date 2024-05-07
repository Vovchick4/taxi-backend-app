<?php

declare(strict_types=1);

use App\Orchid\Screens\Driver\DriverEditScreen;
use App\Orchid\Screens\Driver\DriverListScreen;
use Tabuna\Breadcrumbs\Trail;
use Illuminate\Support\Facades\Route;
use App\Orchid\Screens\PlatformScreen;
use App\Orchid\Screens\Order\OrderScreen;
use App\Orchid\Screens\Role\RoleEditScreen;
use App\Orchid\Screens\Role\RoleListScreen;
use App\Orchid\Screens\Taxi\TaxiEditScreen;
use App\Orchid\Screens\Taxi\TaxiListScreen;
use App\Orchid\Screens\User\UserEditScreen;
use App\Orchid\Screens\User\UserListScreen;
use App\Orchid\Screens\User\UserProfileScreen;
use App\Orchid\Screens\Examples\ExampleScreen;
use App\Orchid\Screens\CarClass\CarClassEditScreen;
use App\Orchid\Screens\CarClass\CarClassListScreen;
use App\Orchid\Screens\Examples\ExampleChartsScreen;

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the need "dashboard" middleware group. Now create something great!
|
*/

// Main
Route::screen('/main', PlatformScreen::class)
    ->name('platform.main');

// Platform > Profile
Route::screen('profile', UserProfileScreen::class)
    ->name('platform.profile')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Profile'), route('platform.profile')));

// Platform > System > Users > User
Route::screen('users/{user}/edit', UserEditScreen::class)
    ->name('platform.systems.users.edit')
    ->breadcrumbs(fn (Trail $trail, $user) => $trail
        ->parent('platform.systems.users')
        ->push($user->name, route('platform.systems.users.edit', $user)));

// Platform > System > Users > Create
Route::screen('users/create', UserEditScreen::class)
    ->name('platform.systems.users.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.users')
        ->push(__('Create'), route('platform.systems.users.create')));

// Platform > System > Users
Route::screen('users', UserListScreen::class)
    ->name('platform.systems.users')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Users'), route('platform.systems.users')));

// Platform > System > Roles > Role
Route::screen('roles/{role}/edit', RoleEditScreen::class)
    ->name('platform.systems.roles.edit')
    ->breadcrumbs(fn (Trail $trail, $role) => $trail
        ->parent('platform.systems.roles')
        ->push($role->name, route('platform.systems.roles.edit', $role)));

// Platform > System > Roles > Create
Route::screen('roles/create', RoleEditScreen::class)
    ->name('platform.systems.roles.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.systems.roles')
        ->push(__('Create'), route('platform.systems.roles.create')));

// Platform > System > Roles
Route::screen('roles', RoleListScreen::class)
    ->name('platform.systems.roles')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Roles'), route('platform.systems.roles')));

// Example...
Route::screen('example', ExampleScreen::class)
    ->name('platform.example')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Example Screen'));

Route::screen('/examples/charts', ExampleChartsScreen::class)->name('platform.example.charts');

//ScreensOrder
Route::screen('orders', OrderScreen::class)->name('platform.screens.orders');

//ScreensCarClasses
Route::screen('car_classes', CarClassListScreen::class)
    ->name('platform.screens.car_classes')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Car class'), route('platform.screens.car_classes')));

Route::screen('car_classes/{carClass}/edit', CarClassEditScreen::class)
    ->name('platform.screens.car_classes.edit')
    ->breadcrumbs(fn (Trail $trail, $carClass) => $trail
        ->parent('platform.screens.car_classes')
        ->push($carClass->name, route('platform.screens.car_classes.edit', $carClass->id)));

Route::screen('car_classes/create', CarClassEditScreen::class)
    ->name('platform.screens.car_classes.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.screens.car_classes')
        ->push(__('Create'), route('platform.screens.car_classes.create')));

// Taxi
Route::screen('taxi', TaxiListScreen::class)
    ->name('platform.screens.taxi')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Taxi'), route('platform.screens.taxi')));

Route::screen('taxi/{taxi}/edit', TaxiEditScreen::class)
    ->name('platform.screens.taxi.edit')
    ->breadcrumbs(fn (Trail $trail, $taxi) => $trail
        ->parent('platform.screens.taxi')
        ->push($taxi->model, route('platform.screens.taxi.edit', $taxi->id)));

Route::screen('taxi/create', TaxiEditScreen::class)
    ->name('platform.screens.taxi.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.screens.taxi')
        ->push(__('Create'), route('platform.screens.taxi.create')));

// Driver
Route::screen('driver', DriverListScreen::class)
    ->name('platform.screens.driver')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push(__('Driver'), route('platform.screens.driver')));

Route::screen('driver/{driver}/edit', DriverEditScreen::class)
    ->name('platform.screens.driver.edit')
    ->breadcrumbs(fn (Trail $trail, $driver) => $trail
        ->parent('platform.screens.driver')
        ->push($driver->name, route('platform.screens.driver.edit', $driver->id)));

Route::screen('driver/create', DriverEditScreen::class)
    ->name('platform.screens.driver.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.screens.driver')
        ->push(__('Create'), route('platform.screens.driver.create')));

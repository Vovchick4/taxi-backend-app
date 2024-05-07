<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Driver;

use Illuminate\Validation\Rule;
use App\Models\Driver;
use App\Orchid\Layouts\Driver\DriverEditLayout;
use App\Orchid\Layouts\Driver\DriverTaxiRelationLayout;
use Orchid\Screen\Action;
use Orchid\Support\Color;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;

class DriverEditScreen extends Screen
{
    /**
     * @var Driver
     */
    public $driver;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Driver $driver): iterable
    {
        $driver->load(['orders', 'taxi']);

        return [
            'driver' => $driver,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->driver->exists ? 'Edit driver' : 'Create driver';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Driver';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.screen.driver',
        ];
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('bs.trash3')
                ->confirm(__('Do you wont delete a driver?'))
                ->method('remove')
                ->canSee($this->driver->exists),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block(DriverEditLayout::class)
                ->title(__('Base information'))
                ->description(__('Update your base information.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->driver->exists)
                        ->method('save')
                ),

            Layout::block(DriverTaxiRelationLayout::class)
                ->title(__('Taxi information'))
                ->description(__('Update your taxi information.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->driver->exists)
                        ->method('save')
                ),
        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Driver $driver, Request $request)
    {
        // Validate request data
        $request->validate([
            'driver.name' => 'required',
            'driver.surname' => 'required',
            'driver.phone' => ['required', Rule::unique(Driver::class, 'phone')->ignore($driver)],
            'driver.email' => Rule::unique(Driver::class, 'email')->ignore($driver),
            'driver.taxi' => 'required',
        ]);

        // Fill the model with the entire 'car_class' array from the request
        $driver->fill($request->input('driver'));

        // Realtion with taxi
        $driver->taxi()->associate($request->input('driver.taxi'));

        // Save the model
        $driver->save();

        // Show a toast notification
        Toast::info(__('Driver was saved.'));

        // Redirect the user to the specified route
        return redirect()->route('platform.screens.driver');
    }

    /**
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Driver $driver)
    {
        $driver->delete();

        Toast::info(__('Driver was removed'));

        return redirect()->route('platform.screens.driver');
    }
}

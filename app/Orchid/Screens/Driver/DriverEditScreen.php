<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Driver;

use Illuminate\Validation\Rule;
use App\Models\Taxi;
use App\Models\Driver;
use App\Orchid\Layouts\Driver\DriverEditLayout;
use App\Orchid\Layouts\Driver\DriverTaxiRelationLayout;
use App\Orchid\Layouts\Driver\DriverPassportDataLayout;
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

            Layout::block(DriverPassportDataLayout::class)
                ->title(__('Driver passport'))
                ->description(__('Update driver passport.'))
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
            'driver.city' => 'required|string|max:255',
            'driver.name' => 'required|string|max:255',
            'driver.surname' => 'required|string|max:255',
            'driver.phone' => [
                'required',
                'string',
                'max:255',
                Rule::unique(Driver::class, 'phone')->ignore($driver),
            ],
            'driver.email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique(Driver::class, 'email')->ignore($driver),
            ],
            'driver.taxi' => 'nullable|exists:taxi,id', // Ensure taxi exists in the taxis table
            'driver.passport_image' => 'required',
            'driver.passport_expiration_date' => 'required|date'
        ]);

        // Fill the driver model with validated data
        $driver->fill($request->input('driver'));

        // Associate the taxi if provided
        if ($request->input('driver.taxi')) {
            $taxiId = $request->input('driver.taxi');
            $taxi = Taxi::find($taxiId);
            if ($taxi) {
                $driver->taxi()->associate($taxi);
            } else {
                return redirect()->back()->withErrors(['driver.taxi' => 'Invalid taxi provided.']);
            }
        } else {
            // If no taxi provided, disassociate any existing taxi
            $driver->taxi()->dissociate();
        }

        // Save the driver model
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

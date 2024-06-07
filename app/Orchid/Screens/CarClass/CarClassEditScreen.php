<?php

declare(strict_types=1);

namespace App\Orchid\Screens\CarClass;

use App\Models\CarClass;
use App\Orchid\Layouts\CarClass\CarClassTariffEditLayout;
use Orchid\Screen\Action;
use Orchid\Support\Color;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\CarClass\CarClassEditLayout;
// use App\Orchid\Layouts\CarClass\CarClassRelationTaxiLayout;

class CarClassEditScreen extends Screen
{
    /**
     * @var CarClass
     */
    public $carClass;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(CarClass $carClass): iterable
    {
        // $carClass->load(['taxis']);

        return [
            'carClass' => $carClass,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->carClass->exists ? 'Edit Car class' : 'Create Car class';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Car class';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.screen.car_classes',
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
                ->confirm(__('Do you wont delete car class?'))
                ->method('remove')
                ->canSee($this->carClass->exists),

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

            Layout::block(CarClassEditLayout::class)
                ->title(__('Base information'))
                ->description(__('Update your base information.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->carClass->exists)
                        ->method('save')
                ),


            Layout::block(CarClassTariffEditLayout::class)
                ->title(__('Tariff information'))
                ->description(__('Set tariff by 1 km price.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->carClass->exists)
                        ->method('save')
                ),


            // Layout::block(CarClassRelationTaxiLayout::class)
            //     ->title(__('Choose taxi'))
            //     ->description(__('Collect taxi model'))
            //     ->commands(
            //         Button::make(__('Save'))
            //             ->type(Color::BASIC)
            //             ->icon('bs.check-circle')
            //             ->canSee($this->carClass->exists)
            //             ->method('save')
            //     ),

        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(CarClass $carClass, Request $request)
    {
        // Validate request data
        $request->validate([
            'carClass.name' => 'required',
            'carClass.slug' => 'required',
            'carClass.tariff_name' => 'required',
            'carClass.tariff_price' => 'required',
        ]);

        // Fill the model with the entire 'car_class' array from the request
        $carClass->fill($request->input('carClass'));

        // Save the model
        $carClass->save();

        // Show a toast notification
        Toast::info(__('Car class was saved.'));

        // Redirect the user to the specified route
        return redirect()->route('platform.screens.car_classes');
    }

    /**
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(CarClass $carClass)
    {
        $carClass->delete();

        Toast::info(__('Car class was removed'));

        return redirect()->route('platform.screens.car_classes');
    }
}

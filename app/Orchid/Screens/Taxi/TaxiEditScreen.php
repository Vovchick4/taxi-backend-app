<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Taxi;

use App\Models\Taxi;
use App\Orchid\Layouts\Taxi\TaxiInsurancePolicyLayout;
use App\Orchid\Layouts\Taxi\TaxiTeachPassLayout;
use Orchid\Screen\Action;
use Orchid\Support\Color;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Taxi\TaxiEditLayout;
use App\Orchid\Layouts\Taxi\TaxiColorLayout;
use App\Orchid\Layouts\Taxi\TaxiRelationClassLayout;

class TaxiEditScreen extends Screen
{
    /**
     * @var Taxi
     */
    public $taxi;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Taxi $taxi): iterable
    {
        $taxi->load(['car_class', 'driver']);

        return [
            'taxi' => $taxi,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return $this->taxi->exists ? 'Edit taxi' : 'Create taxi';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Taxi';
    }

    public function permission(): ?iterable
    {
        return [
            'platform.screen.taxi',
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
                ->confirm(__('Do you wont delete taxi?'))
                ->method('remove')
                ->canSee($this->taxi->exists),

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

            Layout::block(TaxiEditLayout::class)
                ->title(__('Base information'))
                ->description(__('Update your base information.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->taxi->exists)
                        ->method('save')
                ),

            Layout::block(TaxiColorLayout::class)
                ->title(__('Taxi color'))
                ->description(__('Change taxi color.'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->taxi->exists)
                        ->method('save')
                ),


            Layout::block(TaxiRelationClassLayout::class)
                ->title(__('Choose taxi class'))
                ->description(__('Collect class name'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->taxi->exists)
                        ->method('save')
                ),

            Layout::block(TaxiInsurancePolicyLayout::class)
                ->title(__('Choose insurance policy taxi'))
                ->description(__('Collect insurance policy taxi'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->taxi->exists)
                        ->method('save')
                ),

            Layout::block(TaxiTeachPassLayout::class)
                ->title(__('Choose teach taxi password'))
                ->description(__('Collect taxi teach passport'))
                ->commands(
                    Button::make(__('Save'))
                        ->type(Color::BASIC)
                        ->icon('bs.check-circle')
                        ->canSee($this->taxi->exists)
                        ->method('save')
                ),

            // Layout::block(TaxiDriverLayout::class)
            //     ->title(__('Choose a driver'))
            //     ->description(__('Collect driver'))
            //     ->commands(
            //         Button::make(__('Save'))
            //             ->type(Color::BASIC)
            //             ->icon('bs.check-circle')
            //             ->canSee($this->taxi->exists)
            //             ->method('save')
            //     ),

        ];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Taxi $taxi, Request $request)
    {
        // Validate request data
        $request->validate([
            'taxi.model' => 'required',
            'taxi.brand' => 'required',
            'taxi.graduation_year' => 'required',
            'taxi.VIN_code' => 'required',
            'taxi.color' => 'required',
            'taxi.car_class' => 'required',
            'taxi.insurance_policy_date' => 'required',
            'taxi.insurance_policy_image' => 'required',
            'taxi.tech_passport_date' => 'required',
            'taxi.tech_passport_image' => 'required',
        ]);

        // Fill the model with the entire 'car_class' array from the request
        $taxi->fill($request->input('taxi'));

        // Relation with car class
        $taxi->car_class()->associate($request->input('taxi.car_class'));

        // if ($request->input('taxi.driver')) {
        //     // Relation with driver
        //     $taxi->driver()->associate($request->input('taxi.driver'));
        // }

        // Save the model
        $taxi->save();

        // Show a toast notification
        Toast::info(__('Taxi was saved.'));

        // Redirect the user to the specified route
        return redirect()->route('platform.screens.taxi');
    }

    /**
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Taxi $taxi)
    {
        $taxi->delete();

        Toast::info(__('Taxi was removed'));

        return redirect()->route('platform.screens.taxi');
    }
}

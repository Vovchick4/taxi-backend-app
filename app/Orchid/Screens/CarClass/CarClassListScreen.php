<?php

namespace App\Orchid\Screens\CarClass;

use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\CarClass\CarClassListLayout;
use App\Models\CarClass;

class CarClassListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'car_classes' => CarClass::with('taxis')
                ->paginate(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Car classes';
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
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('platform.screens.car_classes.create'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            CarClassListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        CarClass::findOrFail($request->get('id'))->delete();

        Toast::info(__('Car class was removed'));
    }
}

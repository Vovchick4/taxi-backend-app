<?php

namespace App\Orchid\Screens\Taxi;

use App\Models\Taxi;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\Taxi\TaxiListLayout;
use App\Orchid\Layouts\Taxi\TaxiFiltersLayout;

class TaxiListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'taxi' => Taxi::with('car_class')
                ->filters(TaxiFiltersLayout::class)
                ->defaultSort('id', 'desc')
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
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('platform.screens.taxi.create'),
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
            TaxiFiltersLayout::class,
            TaxiListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        Taxi::findOrFail($request->get('id'))->delete();

        Toast::info(__('Taxi was removed'));
    }
}

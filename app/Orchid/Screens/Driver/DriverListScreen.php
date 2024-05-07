<?php

namespace App\Orchid\Screens\Driver;

use App\Models\Driver;
use App\Orchid\Layouts\Driver\DriverListLayout;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Toast;

class DriverListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'drivers' => Driver::with('orders')
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
        return 'Drivers';
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
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make(__('Add'))
                ->icon('bs.plus-circle')
                ->route('platform.screens.driver.create'),
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
            DriverListLayout::class,
        ];
    }

    public function remove(Request $request): void
    {
        Driver::findOrFail($request->get('id'))->delete();

        Toast::info(__('Driver was removed'));
    }
}

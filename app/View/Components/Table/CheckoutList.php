<?php

namespace App\View\Components\Table;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CheckoutList extends Component
{
    public $dataTable;
    /**
     * Create a new component instance.
     */
    public function __construct($data)
    {
        $this->dataTable = $data;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table.checkout-list');
    }

    public static function setDataTable(array|null $thead, array|null $tbody) : array {
        return [
            'thead'=> $thead,
            'tbody' => $tbody
        ];
    }
}

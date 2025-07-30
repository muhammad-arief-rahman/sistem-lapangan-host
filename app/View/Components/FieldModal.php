<?php

namespace App\View\Components;

use App\Models\District;
use App\Models\Facilities;
use App\Models\User;
use App\Services\RegionService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FieldModal extends Component
{

    public $facilities = [];
    public $districts = [];
    public $managers = [];

    public function __construct(
        public $field = null,

    ) {
        $this->facilities = Facilities::orderBy('name')->get();
        $this->districts = RegionService::getDistricts();
        $this->managers = User::where('role', 'field_manager')->get();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.field-modal');
    }
}

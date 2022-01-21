<?php

namespace App\Http\Livewire;

use App\Models\Idea;
use App\Models\Status;
use Illuminate\Support\Facades\Route;
use Livewire\Component;

class StatusFilters extends Component
{
    public $status = 'All';
    public $statusCount;

    protected $queryString = [
        'status' => ['except' => ''],
    ];

    public function mount()
    {
        $this->statusCount = Status::getCount();

        if (Route::currentRouteName() === 'ideas.show') {
            $this->status = null;
        }
    }

    public function setStatus($newStatus)
    {
        $this->status = $newStatus;

        //  if ($this->getPreviousRouteName() === 'ideas.show') {
        return redirect()->route('ideas.index', [
            'status' => $this->status,
        ]);
        // }
    }

    public function render()
    {
        return view('livewire.status-filters');
    }

    private function getPreviousRouteName()
    {
        return app('router')
            ->getRoutes()
            ->match(
                app('request')->create(url()->previous())
            )
            ->getName();
    }
}

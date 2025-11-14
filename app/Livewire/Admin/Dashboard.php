<?php

namespace App\Livewire\Admin;

use App\Models\Course;
use App\Models\FinalTest;
use App\Models\Lesson;
use App\Models\Module;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public string $search = '';
    public string $status = 'all';
    public int $perPage = 5;

    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => 'all'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatus(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $stats = [
            'courses' => Course::count(),
            'modules' => Module::count(),
            'lessons' => Lesson::count(),
            'final_tests' => FinalTest::count(),
        ];

        $courses = Course::with(['owner', 'modules.lessons', 'finalTest'])
            ->when($this->search, fn ($query) => $query->where('title', 'like', '%'.$this->search.'%'))
            ->when($this->status !== 'all', fn ($query) => $query->where('status', $this->status))
            ->orderByDesc('created_at')
            ->paginate($this->perPage);

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'courses' => $courses,
        ]);
    }
}

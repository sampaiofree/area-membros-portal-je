<?php

namespace App\Livewire\Student;

use App\Models\Course;
use Livewire\Component;

class Catalog extends Component
{
    public string $search = '';

    public function render()
    {
        $courses = Course::query()
            ->withCount('enrollments')
            ->where('status', 'published')
            ->when($this->search !== '', fn ($query) => $query->where('title', 'like', '%'.$this->search.'%'))
            ->orderBy('title')
            ->get();

        return view('livewire.student.catalog', compact('courses'));
    }
}

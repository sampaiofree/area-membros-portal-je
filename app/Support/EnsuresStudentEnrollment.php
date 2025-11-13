<?php

namespace App\Support;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;

trait EnsuresStudentEnrollment
{
    protected function ensureEnrollment(User $user, Course $course): Enrollment
    {
        $enrollment = Enrollment::query()
            ->where('course_id', $course->id)
            ->where('user_id', $user->id)
            ->first();

        abort_if(! $enrollment, 403, 'Você não está matriculado neste curso.');

        return $enrollment;
    }
}

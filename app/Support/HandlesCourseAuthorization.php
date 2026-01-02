<?php

namespace App\Support;

use App\Models\Course;
use App\Models\User;

trait HandlesCourseAuthorization
{
    protected function ensureCanManageCourse(User $user, Course $course): void
    {
        if ($user->isAdmin()) {
            return;
        }

        abort(403);
    }
}

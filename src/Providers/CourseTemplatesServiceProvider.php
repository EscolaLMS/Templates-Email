<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Courses\Events\CourseAssigned;
use EscolaLms\Courses\Events\CourseCompleted;
use EscolaLms\Courses\Events\CourseUnassigned;
use EscolaLms\Courses\Events\DeadlineIncoming;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use EscolaLms\TemplatesEmail\Courses\DeadlineIncomingVariables;
use EscolaLms\TemplatesEmail\Courses\UserAssignedToCourseVariables;
use EscolaLms\TemplatesEmail\Courses\UserFinishedCourseVariables;
use EscolaLms\TemplatesEmail\Courses\UserUnassignedFromCourseVariables;
use Illuminate\Support\ServiceProvider;

class CourseTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(DeadlineIncoming::class, EmailChannel::class, DeadlineIncomingVariables::class);
        Template::register(CourseCompleted::class, EmailChannel::class, UserFinishedCourseVariables::class);
        Template::register(CourseAssigned::class, EmailChannel::class, UserAssignedToCourseVariables::class);
        Template::register(CourseUnassigned::class, EmailChannel::class, UserUnassignedFromCourseVariables::class);
    }
}

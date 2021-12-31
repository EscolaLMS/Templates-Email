<?php

namespace EscolaLms\TemplatesEmail\Providers;

use EscolaLms\Courses\Events\EscolaLmsCourseAccessFinishedTemplateEvent;
use EscolaLms\Courses\Events\EscolaLmsCourseAccessStartedTemplateEvent;
use EscolaLms\Courses\Events\EscolaLmsCourseAssignedTemplateEvent;
use EscolaLms\Courses\Events\EscolaLmsCourseDeadlineSoonTemplateEvent;
use EscolaLms\Courses\Events\EscolaLmsCoursedPublishedTemplateEvent;
use EscolaLms\Courses\Events\EscolaLmsCourseFinishedTemplateEvent;
use EscolaLms\Courses\Events\EscolaLmsCourseStartedTemplateEvent;
use EscolaLms\Courses\Events\EscolaLmsCourseUnassignedTemplateEvent;
use EscolaLms\Courses\Events\EscolaLmsTopicFinishedTemplateEvent;
use EscolaLms\Templates\Facades\Template;
use EscolaLms\TemplatesEmail\Core\EmailChannel;
use EscolaLms\TemplatesEmail\Courses\AccessFinishedCourseVariables;
use EscolaLms\TemplatesEmail\Courses\AccessStartedCourseVariables;
use EscolaLms\TemplatesEmail\Courses\DeadlineIncomingVariables;
use EscolaLms\TemplatesEmail\Courses\PublishedCourseVariables;
use EscolaLms\TemplatesEmail\Courses\StartedCourseVariables;
use EscolaLms\TemplatesEmail\Courses\TopicFinishedCourseVariables;
use EscolaLms\TemplatesEmail\Courses\UserAssignedToCourseVariables;
use EscolaLms\TemplatesEmail\Courses\UserFinishedCourseVariables;
use EscolaLms\TemplatesEmail\Courses\UserUnassignedFromCourseVariables;
use EscolaLms\TemplatesEmail\TopicTypes\TopicTypeChangedVariables;
use EscolaLms\TopicTypes\Events\EscolaLmsTopicTypeChangedTemplateEvent;
use Illuminate\Support\ServiceProvider;

class TopicTypesTemplatesServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Template::register(
            EscolaLmsTopicTypeChangedTemplateEvent::class,
            EmailChannel::class,
            TopicTypeChangedVariables::class
        );
    }
}

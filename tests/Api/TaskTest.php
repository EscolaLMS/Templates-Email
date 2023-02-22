<?php

namespace EscolaLms\TemplatesEmail\Tests\Api;

use EscolaLms\Tasks\Database\Seeders\TaskPermissionSeeder;
use EscolaLms\Tasks\Events\TaskAssignedEvent;
use EscolaLms\Tasks\Models\Task;
use EscolaLms\Tasks\Tests\CreatesUsers;
use EscolaLms\Templates\Listeners\TemplateEventListener;
use EscolaLms\TemplatesEmail\Core\EmailMailable;
use EscolaLms\TemplatesEmail\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class TaskTest extends TestCase
{
    use CreatesUsers, DatabaseTransactions, WithFaker;

    public function setUp(): void
    {
        parent::setUp();

        if (!class_exists(\EscolaLms\Tasks\EscolaLmsTasksServiceProvider::class)) {
            $this->markTestSkipped('Task package not installed');
        }

        $this->seed(TaskPermissionSeeder::class);
        Notification::fake();
        Mail::fake();
    }

    public function testNotificationOnTaskAssignment(): void
    {
        Event::fake([TaskAssignedEvent::class]);

        $admin = $this->makeAdmin();
        $student = $this->makeStudent();

        $this->actingAs($admin, 'api')
            ->postJson('api/admin/tasks', [
                'title' => $this->faker->title,
                'user_id' => $student->getKey(),
                'related_type' => null,
                'related_id' => null,
            ])
            ->assertCreated();

        Event::assertDispatched(function (TaskAssignedEvent $event) use ($student) {
            $this->assertEquals($student->getKey(), $event->getUser()->getKey());
            return true;
        });

        $task = Task::latest()->first();
        $listener = app(TemplateEventListener::class);
        $listener->handle(new TaskAssignedEvent($task->user, $task));

        Mail::assertSent(EmailMailable::class, function (EmailMailable $mailable) use ($student, $task) {
            $this->assertEquals(__('Task ":task" assigned', ['task' => $task->title]), $mailable->subject);
            $this->assertTrue($mailable->hasTo($student->email));
            return true;
        });
    }
}

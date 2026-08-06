<?php

namespace Tests\Feature;

use App\Models\Conversation;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * ADR-0003: parent/teacher ↔ institution chat — polling-based real vaqtlilik +
 * ustoz suhbatining qo'shilishi. CSRF middleware o'chiriladi (AJAX/JSON so'rovlar
 * test muhitida token ololmaydi, sinov faqat marshrut/policy mantig'ini tekshiradi).
 */
class ChatTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([ValidateCsrfToken::class, VerifyCsrfToken::class]);
    }

    private function makeInstitution(): Institution
    {
        $owner = User::factory()->create(['role' => User::ROLE_INSTITUTION]);

        return Institution::create([
            'owner_user_id' => $owner->id,
            'name' => 'Bilim Ziyo maktabi',
            'type' => 'maktab',
        ]);
    }

    public function test_parent_can_start_conversation_via_shared_endpoint(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $institution = $this->makeInstitution();

        $response = $this->actingAs($parent)->postJson('/ajax/conversations', [
            'institution_id' => $institution->id,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('conversation.parent_user_id', $parent->id);
        $response->assertJsonPath('conversation.teacher_user_id', null);
        $this->assertStringContainsString('/cabinet/conversations', $response->json('redirect'));
    }

    public function test_teacher_can_start_conversation_via_same_shared_endpoint(): void
    {
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $institution = $this->makeInstitution();

        $response = $this->actingAs($teacher)->postJson('/ajax/conversations', [
            'institution_id' => $institution->id,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('conversation.teacher_user_id', $teacher->id);
        $response->assertJsonPath('conversation.parent_user_id', null);
        $this->assertStringContainsString('/teacher-cabinet/conversations', $response->json('redirect'));
    }

    public function test_institution_owner_cannot_start_a_conversation(): void
    {
        $institution = $this->makeInstitution();
        $owner = $institution->owner;

        $this->actingAs($owner)->postJson('/ajax/conversations', [
            'institution_id' => $institution->id,
        ])->assertForbidden();
    }

    public function test_teacher_send_and_institution_reply_round_trip(): void
    {
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $institution = $this->makeInstitution();

        $conversation = Conversation::create([
            'teacher_user_id' => $teacher->id,
            'institution_id' => $institution->id,
            'last_message_at' => now(),
        ]);

        $this->actingAs($teacher)
            ->postJson("/ajax/teacher/conversations/{$conversation->id}/messages", ['body' => 'Salom, vakansiya bormi?'])
            ->assertCreated()
            ->assertJsonPath('message.sender_type', 'teacher')
            ->assertJsonPath('message.mine', true);

        $this->actingAs($institution->owner)
            ->postJson("/ajax/institution/me/conversations/{$conversation->id}/messages", ['body' => 'Ha, bor.'])
            ->assertCreated()
            ->assertJsonPath('message.sender_type', 'institution');

        $this->assertSame(2, $conversation->messages()->count());
    }

    public function test_another_teacher_cannot_view_or_message_someone_elses_conversation(): void
    {
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $intruder = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $institution = $this->makeInstitution();

        $conversation = Conversation::create([
            'teacher_user_id' => $teacher->id,
            'institution_id' => $institution->id,
            'last_message_at' => now(),
        ]);

        $this->actingAs($intruder)
            ->getJson("/ajax/conversations/{$conversation->id}/messages")
            ->assertForbidden();

        $this->actingAs($intruder)
            ->postJson("/ajax/teacher/conversations/{$conversation->id}/messages", ['body' => 'Salom'])
            ->assertForbidden();
    }

    public function test_polling_endpoint_only_returns_messages_after_given_id(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $institution = $this->makeInstitution();

        $conversation = Conversation::create([
            'parent_user_id' => $parent->id,
            'institution_id' => $institution->id,
            'last_message_at' => now(),
        ]);

        $first = $conversation->messages()->create([
            'sender_type' => 'parent', 'sender_user_id' => $parent->id, 'body' => 'Birinchi',
        ]);
        $second = $conversation->messages()->create([
            'sender_type' => 'institution', 'sender_user_id' => $institution->owner_user_id, 'body' => 'Ikkinchi',
        ]);

        $response = $this->actingAs($parent)
            ->getJson("/ajax/conversations/{$conversation->id}/messages?after_id={$first->id}");

        $response->assertOk();
        $ids = collect($response->json('messages'))->pluck('id');
        $this->assertEquals([$second->id], $ids->all());
    }

    public function test_polling_marks_counterpart_messages_as_read(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $institution = $this->makeInstitution();

        $conversation = Conversation::create([
            'parent_user_id' => $parent->id,
            'institution_id' => $institution->id,
            'last_message_at' => now(),
        ]);

        $message = $conversation->messages()->create([
            'sender_type' => 'institution', 'sender_user_id' => $institution->owner_user_id, 'body' => 'Salom',
        ]);

        $this->actingAs($parent)->getJson("/ajax/conversations/{$conversation->id}/messages")->assertOk();

        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_institution_conversations_list_includes_both_parent_and_teacher_threads(): void
    {
        $institution = $this->makeInstitution();
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);

        Conversation::create(['parent_user_id' => $parent->id, 'institution_id' => $institution->id, 'last_message_at' => now()]);
        Conversation::create(['teacher_user_id' => $teacher->id, 'institution_id' => $institution->id, 'last_message_at' => now()]);

        $this->assertSame(2, $institution->conversations()->count());
    }
}

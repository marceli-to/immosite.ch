<?php

namespace Tests\Feature;

use Tests\TestCase;

class FaqTest extends TestCase
{
    public function test_faq_page_lists_configured_questions(): void
    {
        config(['faq' => [
            ['question' => 'Frage eins?', 'answer' => '<p>Antwort <strong>eins</strong>.</p>'],
            ['question' => 'Frage zwei?', 'answer' => '<p>Antwort zwei.</p>'],
        ]]);

        $this->get(route('page.faq'))
            ->assertOk()
            ->assertSeeInOrder(['Frage eins?', 'Frage zwei?'])
            ->assertSee('<strong>eins</strong>', false)
            ->assertSee('aria-controls="accordion-', false);
    }
}

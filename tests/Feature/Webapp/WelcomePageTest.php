<?php

declare(strict_types=1);

namespace Tests\Feature\Webapp;

use Tests\TestCase;

class WelcomePageTest extends TestCase
{
    public function test_home_page_renders_the_foundation_message(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Foundation ready');
    }
}

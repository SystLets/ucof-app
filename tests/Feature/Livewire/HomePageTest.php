<?php

declare(strict_types=1);

namespace Tests\Feature\Livewire;

use App\Livewire\HomePage;
use Livewire\Livewire;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    public function test_livewire_home_page_shows_the_foundation_state(): void
    {
        Livewire::test(HomePage::class)
            ->assertSee('Foundation ready');
    }
}

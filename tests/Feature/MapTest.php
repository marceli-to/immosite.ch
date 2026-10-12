<?php

namespace Tests\Feature;

use Tests\TestCase;

class MapTest extends TestCase
{
    public function test_home_page_renders_the_map_with_config(): void
    {
        config(['map.token' => 'pk.test', 'map.center' => [8.5, 47.3], 'map.zoom' => 14]);

        $response = $this->get(route('page.home'))->assertOk();

        preg_match("/data-map='([^']+)'/", $response->getContent(), $m);
        $config = json_decode(html_entity_decode($m[1] ?? ''), true);

        $this->assertSame('pk.test', $config['token']);
        $this->assertSame([8.5, 47.3], $config['center']);
        $this->assertSame(14, $config['zoom']);
        $this->assertNotEmpty($config['markers']);
    }

    public function test_map_renders_without_token(): void
    {
        config(['map.token' => null]);

        $this->get(route('page.home'))->assertOk()->assertSee('data-map=', false);
    }

    public function test_privacy_page_mentions_mapbox(): void
    {
        $this->get(route('page.privacy'))->assertSee('Mapbox');
    }
}

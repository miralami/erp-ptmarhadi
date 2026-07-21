<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_dashboard_shows_kpi_cards(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Total Order');
        $response->assertSee('Pengiriman Aktif');
        $response->assertSee('Outstanding Invoice');
        $response->assertSee('Piutang');
        $response->assertSee('Invoice Overdue');
        $response->assertSee('Dibayar Bulan Ini');
    }

    public function test_dashboard_shows_chart_sections(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Revenue Bulanan');
        $response->assertSee('Distribusi Status Invoice');
        $response->assertSee('Top 5 Customers');
        $response->assertSee('Tren Pembayaran');
    }

    public function test_dashboard_shows_recent_widgets(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Order Terbaru');
        $response->assertSee('Pembayaran Terbaru');
        $response->assertSee('Aktivitas Terbaru');
    }
}

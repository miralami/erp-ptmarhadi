<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Services\OrderStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatusTransitionTest extends TestCase
{
    use RefreshDatabase;

    private OrderStatusService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(OrderStatusService::class);
    }

    public function test_allows_valid_transition(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::ORDER_RECEIVED]);

        $this->service->transition($order, OrderStatus::PERJALANAN_MUAT);

        $this->assertEquals(OrderStatus::PERJALANAN_MUAT, $order->fresh()->status);
    }

    public function test_prevents_invalid_transition(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::ORDER_RECEIVED]);

        $this->expectException(\InvalidArgumentException::class);

        $this->service->transition($order, OrderStatus::COMPLETED);
    }

    public function test_allows_cancellation_from_any_active_state(): void
    {
        $statuses = [
            OrderStatus::ORDER_RECEIVED,
            OrderStatus::PERJALANAN_MUAT,
            OrderStatus::PERJALANAN_BONGKAR,
        ];

        foreach ($statuses as $status) {
            $order = Order::factory()->create(['status' => $status]);
            $this->service->transition($order, OrderStatus::CANCELLED);
            $this->assertEquals(OrderStatus::CANCELLED, $order->fresh()->status);
        }
    }

    public function test_prevents_transition_from_cancelled(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::CANCELLED]);

        $this->expectException(\InvalidArgumentException::class);

        $this->service->transition($order, OrderStatus::ORDER_RECEIVED);
    }

    public function test_full_valid_flow(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::ORDER_RECEIVED]);

        $transitions = [
            OrderStatus::PERJALANAN_MUAT,
            OrderStatus::PERJALANAN_BONGKAR,
            OrderStatus::COMPLETED,
        ];

        foreach ($transitions as $status) {
            $this->service->transition($order, $status);
            $this->assertEquals($status, $order->fresh()->status);
        }
    }

    public function test_update_status_via_http_endpoint(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::ORDER_RECEIVED]);

        $response = $this->post(route('orders.update-status', $order), [
            'status' => OrderStatus::PERJALANAN_MUAT->value,
        ]);

        $response->assertSessionHas('success');
        $this->assertEquals(OrderStatus::PERJALANAN_MUAT, $order->fresh()->status);
    }

    public function test_http_endpoint_rejects_invalid_transition(): void
    {
        $order = Order::factory()->create(['status' => OrderStatus::ORDER_RECEIVED]);

        $response = $this->post(route('orders.update-status', $order), [
            'status' => OrderStatus::PERJALANAN_BONGKAR->value,
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(OrderStatus::ORDER_RECEIVED, $order->fresh()->status);
    }
}

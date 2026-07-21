<?php

namespace Tests\Feature;

use App\Enums\DeliveryStatus;
use App\Models\Delivery;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeliveryTest extends TestCase
{
    use RefreshDatabase;

    public function test_deliveries_index_page_is_accessible(): void
    {
        $response = $this->get(route('deliveries.index'));
        $response->assertStatus(200);
    }

    public function test_deliveries_create_page_is_accessible(): void
    {
        Order::factory()->create();

        $response = $this->get(route('deliveries.create'));
        $response->assertStatus(200);
    }

    public function test_can_create_delivery(): void
    {
        $order = Order::factory()->create();

        $response = $this->post(route('deliveries.store'), [
            'order_id' => $order->id,
            'delivery_date' => '2026-07-21',
            'driver_name' => 'Test Driver',
            'notes' => 'Test delivery',
        ]);

        $response->assertRedirect(route('deliveries.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('deliveries', [
            'order_id' => $order->id,
            'driver_name' => 'Test Driver',
            'status' => DeliveryStatus::SCHEDULED->value,
        ]);
    }

    public function test_validation_fails_without_required_fields(): void
    {
        $response = $this->post(route('deliveries.store'), []);
        $response->assertSessionHasErrors(['order_id', 'delivery_date']);
    }

    public function test_can_view_delivery_detail(): void
    {
        $delivery = Delivery::factory()->create();

        $response = $this->get(route('deliveries.show', $delivery));
        $response->assertStatus(200);
        $response->assertSee($delivery->delivery_number);
    }

    public function test_can_update_delivery(): void
    {
        $delivery = Delivery::factory()->create();

        $response = $this->put(route('deliveries.update', $delivery), [
            'order_id' => $delivery->order_id,
            'delivery_date' => '2026-07-21',
            'driver_name' => 'Updated Driver',
            'status' => DeliveryStatus::DELIVERED->value,
            'notes' => 'Updated',
        ]);

        $response->assertRedirect(route('deliveries.index'));
        $delivery->refresh();
        $this->assertEquals('Updated Driver', $delivery->driver_name);
        $this->assertEquals(DeliveryStatus::DELIVERED, $delivery->status);
    }

    public function test_delivery_has_initial_status_scheduled(): void
    {
        $order = Order::factory()->create();

        $this->post(route('deliveries.store'), [
            'order_id' => $order->id,
            'delivery_date' => '2026-07-21',
        ]);

        $delivery = Delivery::first();
        $this->assertEquals(DeliveryStatus::SCHEDULED, $delivery->status);
    }

    public function test_delivery_belongs_to_order(): void
    {
        $order = Order::factory()->create();
        $delivery = Delivery::factory()->create(['order_id' => $order->id]);

        $this->assertEquals($order->id, $delivery->order_id);
        $this->assertEquals($delivery->id, $order->fresh()->delivery->id);
    }
}

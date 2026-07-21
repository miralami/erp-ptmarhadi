<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_orders_index_page_is_accessible(): void
    {
        $response = $this->get(route('orders.index'));
        $response->assertStatus(200);
    }

    public function test_orders_create_page_is_accessible(): void
    {
        $response = $this->get(route('orders.create'));
        $response->assertStatus(200);
    }

    public function test_can_create_order_with_items(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'order_date' => '2026-07-21',
            'notes' => 'Test order',
            'items' => [
                ['product_name' => 'Beras Premium 5kg', 'unit' => 10, 'price' => 75000],
                ['product_name' => 'Minyak Goreng 1L', 'unit' => 5, 'price' => 18000],
            ],
        ]);

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'status' => OrderStatus::ORDER_RECEIVED->value,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_name' => 'Beras Premium 5kg',
            'unit' => 10,
            'price' => 75000,
        ]);

        $this->assertDatabaseHas('order_items', [
            'product_name' => 'Minyak Goreng 1L',
            'unit' => 5,
            'price' => 18000,
        ]);
    }

    public function test_order_number_is_generated_on_create(): void
    {
        $customer = Customer::factory()->create();

        $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'order_date' => '2026-07-21',
            'items' => [
                ['product_name' => 'Test', 'unit' => 1, 'price' => 1000],
            ],
        ]);

        $order = Order::first();
        $this->assertNotNull($order->order_number);
        $this->assertStringStartsWith('ORD-', $order->order_number);
    }

    public function test_validation_fails_without_required_fields(): void
    {
        $response = $this->post(route('orders.store'), []);
        $response->assertSessionHasErrors(['customer_id', 'order_date', 'items']);
    }

    public function test_validation_fails_without_items(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'order_date' => '2026-07-21',
            'items' => [],
        ]);

        $response->assertSessionHasErrors(['items']);
    }

    public function test_validation_fails_with_invalid_customer(): void
    {
        $response = $this->post(route('orders.store'), [
            'customer_id' => 999,
            'order_date' => '2026-07-21',
            'items' => [
                ['product_name' => 'Test', 'unit' => 1, 'price' => 1000],
            ],
        ]);

        $response->assertSessionHasErrors(['customer_id']);
    }

    public function test_can_update_order(): void
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->put(route('orders.update', $order), [
            'customer_id' => $customer->id,
            'order_date' => '2026-07-21',
            'notes' => 'Updated notes',
        ]);

        $response->assertRedirect(route('orders.index'));
        $order->refresh();
        $this->assertEquals('Updated notes', $order->notes);
    }

    public function test_can_view_order_detail(): void
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->get(route('orders.show', $order));
        $response->assertStatus(200);
        $response->assertSee($order->order_number);
    }

    public function test_order_total_is_calculated_correctly(): void
    {
        $order = Order::factory()->create();
        $order->items()->createMany([
            ['product_name' => 'Item A', 'unit' => 10, 'price' => 75000],
            ['product_name' => 'Item B', 'unit' => 5, 'price' => 18000],
        ]);

        $order->load('items');
        $expected = (10 * 75000) + (5 * 18000);
        $this->assertEquals($expected, $order->total);
    }

    public function test_order_has_initial_status_order_received(): void
    {
        $customer = Customer::factory()->create();

        $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'order_date' => '2026-07-21',
            'items' => [
                ['product_name' => 'Test', 'unit' => 1, 'price' => 1000],
            ],
        ]);

        $order = Order::first();
        $this->assertEquals(OrderStatus::ORDER_RECEIVED, $order->status);
    }
}

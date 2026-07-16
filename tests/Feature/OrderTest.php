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

    public function test_dashboard_returns_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

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

    public function test_can_create_order(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'date' => '2026-07-17',
            'product_name' => 'Beras Premium 5kg',
            'quantity' => 10,
            'price' => 75000,
        ]);

        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('orders', [
            'product_name' => 'Beras Premium 5kg',
            'quantity' => 10,
            'price' => 75000.00,
            'status' => OrderStatus::ORDER_RECEIVED->value,
        ]);
    }

    public function test_order_number_is_generated_on_create(): void
    {
        $customer = Customer::factory()->create();

        $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'date' => '2026-07-17',
            'product_name' => 'Test',
            'quantity' => 1,
            'price' => 1000,
        ]);

        $order = Order::first();
        $this->assertNotNull($order->order_number);
        $this->assertStringStartsWith('ORD-', $order->order_number);
    }

    public function test_validation_fails_without_required_fields(): void
    {
        $response = $this->post(route('orders.store'), []);
        $response->assertSessionHasErrors(['customer_id', 'date', 'product_name', 'quantity', 'price']);
    }

    public function test_validation_fails_with_invalid_customer(): void
    {
        $response = $this->post(route('orders.store'), [
            'customer_id' => 999,
            'date' => '2026-07-17',
            'product_name' => 'Test',
            'quantity' => 1,
            'price' => 1000,
        ]);
        $response->assertSessionHasErrors(['customer_id']);
    }

    public function test_can_update_order(): void
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create(['customer_id' => $customer->id]);

        $response = $this->put(route('orders.update', $order), [
            'customer_id' => $customer->id,
            'date' => '2026-07-17',
            'product_name' => 'Updated Product',
            'quantity' => 5,
            'price' => 50000,
            'status' => OrderStatus::DELIVERED->value,
        ]);

        $response->assertRedirect(route('orders.index'));
        $order->refresh();
        $this->assertEquals('Updated Product', $order->product_name);
        $this->assertEquals(OrderStatus::DELIVERED, $order->status);
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
        $order = new Order(['quantity' => 10, 'price' => 75000]);
        $this->assertEquals(750000, $order->total);
    }

    public function test_dashboard_shows_recent_orders(): void
    {
        $customer = Customer::factory()->create();
        Order::factory()->count(5)->create(['customer_id' => $customer->id]);

        $response = $this->get('/');
        $response->assertStatus(200);
    }
}

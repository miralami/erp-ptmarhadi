<?php

namespace Tests\Feature;

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customers_index_page_is_accessible(): void
    {
        $response = $this->get(route('customers.index'));
        $response->assertStatus(200);
    }

    public function test_customers_create_page_is_accessible(): void
    {
        $response = $this->get(route('customers.create'));
        $response->assertStatus(200);
    }

    public function test_can_create_customer(): void
    {
        $response = $this->post(route('customers.store'), [
            'company_name' => 'PT Test Sejahtera',
            'contact_person' => 'Test User',
            'email' => 'test@test.com',
            'phone' => '021-1234567',
            'address' => 'Jl. Test No. 1, Jakarta',
            'notes' => 'Test customer',
        ]);

        $response->assertRedirect(route('customers.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('customers', [
            'company_name' => 'PT Test Sejahtera',
            'email' => 'test@test.com',
        ]);
    }

    public function test_validation_fails_without_company_name(): void
    {
        $response = $this->post(route('customers.store'), []);
        $response->assertSessionHasErrors(['company_name']);
    }

    public function test_can_view_customer_detail(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->get(route('customers.show', $customer));
        $response->assertStatus(200);
        $response->assertSee($customer->company_name);
    }

    public function test_can_update_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->put(route('customers.update', $customer), [
            'company_name' => 'PT Updated Name',
            'contact_person' => 'Updated Person',
            'email' => 'updated@test.com',
            'phone' => '021-9999999',
            'address' => 'Jl. Updated No. 1',
            'notes' => 'Updated',
        ]);

        $response->assertRedirect(route('customers.index'));
        $customer->refresh();
        $this->assertEquals('PT Updated Name', $customer->company_name);
        $this->assertEquals('updated@test.com', $customer->email);
    }

    public function test_can_delete_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->delete(route('customers.destroy', $customer));
        $response->assertRedirect(route('customers.index'));
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }

    public function test_customer_has_orders_relation(): void
    {
        $customer = Customer::factory()->create();
        $order = \App\Models\Order::factory()->create(['customer_id' => $customer->id]);

        $this->assertTrue($customer->orders->contains($order));
    }
}

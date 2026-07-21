<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\OrderStatus;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoices_index_page_is_accessible(): void
    {
        $response = $this->get(route('invoices.index'));
        $response->assertStatus(200);
    }

    public function test_invoices_create_page_is_accessible(): void
    {
        $response = $this->get(route('invoices.create'));
        $response->assertStatus(200);
    }

    public function test_can_create_invoice_from_order(): void
    {
        $customer = Customer::factory()->create();
        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'status' => OrderStatus::COMPLETED,
        ]);
        $order->items()->createMany([
            ['product_name' => 'Item A', 'unit' => 10, 'price' => 75000],
            ['product_name' => 'Item B', 'unit' => 5, 'price' => 18000],
        ]);
        $order->load('items');
        $subtotal = $order->items->sum(fn($item) => $item->unit * $item->price);
        $expectedTotal = $subtotal + ($subtotal * 0.011);

        $response = $this->post(route('invoices.store'), [
            'order_id' => $order->id,
            'invoice_date' => '2026-07-21',
            'due_date' => '2026-08-20',
            'notes' => 'Test invoice',
        ]);

        $response->assertRedirect(route('invoices.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('invoices', [
            'order_id' => $order->id,
            'customer_id' => $customer->id,
            'invoice_total' => $expectedTotal,
            'paid_amount' => 0,
            'status' => InvoiceStatus::DRAFT->value,
        ]);
    }

    public function test_validation_fails_without_required_fields(): void
    {
        $response = $this->post(route('invoices.store'), []);
        $response->assertSessionHasErrors(['order_id', 'invoice_date', 'due_date']);
    }

    public function test_can_view_invoice_detail(): void
    {
        $invoice = Invoice::factory()->create();

        $response = $this->get(route('invoices.show', $invoice));
        $response->assertStatus(200);
        $response->assertSee($invoice->invoice_number);
    }

    public function test_can_update_invoice(): void
    {
        $invoice = Invoice::factory()->create();

        $response = $this->put(route('invoices.update', $invoice), [
            'invoice_date' => '2026-07-21',
            'due_date' => '2026-08-21',
            'status' => InvoiceStatus::SENT->value,
            'notes' => 'Updated invoice',
        ]);

        $response->assertRedirect(route('invoices.index'));
        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::SENT, $invoice->status);
    }

    public function test_can_mark_invoice_as_sent(): void
    {
        $invoice = Invoice::factory()->create(['status' => InvoiceStatus::DRAFT]);

        $response = $this->post(route('invoices.send', $invoice));
        $response->assertSessionHas('success');

        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::SENT, $invoice->status);
    }

    public function test_invoice_pdf_page_is_accessible(): void
    {
        $invoice = Invoice::factory()->create();

        $response = $this->get(route('invoices.pdf', $invoice));
        $response->assertStatus(200);
        $response->assertSee($invoice->invoice_number);
    }

    public function test_invoice_remaining_amount(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_total' => 1000000,
            'paid_amount' => 400000,
        ]);

        $this->assertEquals(600000, $invoice->remaining);
    }
}

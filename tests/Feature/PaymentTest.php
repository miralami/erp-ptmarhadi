<?php

namespace Tests\Feature;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentMethod;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_payments_index_page_is_accessible(): void
    {
        $response = $this->get(route('payments.index'));
        $response->assertStatus(200);
    }

    public function test_payments_create_page_is_accessible(): void
    {
        $response = $this->get(route('payments.create'));
        $response->assertStatus(200);
    }

    public function test_can_create_payment(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_total' => 1000000,
            'paid_amount' => 0,
            'status' => InvoiceStatus::SENT,
        ]);

        $response = $this->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'payment_date' => '2026-07-21',
            'amount' => 1000000,
            'payment_method' => PaymentMethod::TRANSFER->value,
            'reference_number' => 'TRF-001',
            'notes' => 'Full payment',
        ]);

        $response->assertRedirect(route('payments.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'amount' => 1000000,
            'payment_method' => PaymentMethod::TRANSFER->value,
        ]);
    }

    public function test_payment_updates_invoice_status_to_paid(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_total' => 1000000,
            'paid_amount' => 0,
            'status' => InvoiceStatus::SENT,
        ]);

        $this->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'payment_date' => '2026-07-21',
            'amount' => 1000000,
            'payment_method' => PaymentMethod::TRANSFER->value,
        ]);

        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::PAID, $invoice->status);
        $this->assertEquals(1000000, $invoice->paid_amount);
    }

    public function test_partial_payment_updates_invoice_status(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_total' => 1000000,
            'paid_amount' => 0,
            'status' => InvoiceStatus::SENT,
        ]);

        $this->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'payment_date' => '2026-07-21',
            'amount' => 400000,
            'payment_method' => PaymentMethod::TRANSFER->value,
        ]);

        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::PARTIALLY_PAID, $invoice->status);
        $this->assertEquals(400000, $invoice->paid_amount);
    }

    public function test_multiple_payments_on_same_invoice(): void
    {
        $invoice = Invoice::factory()->create([
            'invoice_total' => 1000000,
            'paid_amount' => 0,
            'status' => InvoiceStatus::SENT,
        ]);

        $this->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'payment_date' => '2026-07-21',
            'amount' => 400000,
            'payment_method' => PaymentMethod::TRANSFER->value,
        ]);

        $this->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'payment_date' => '2026-07-22',
            'amount' => 600000,
            'payment_method' => PaymentMethod::CASH->value,
        ]);

        $invoice->refresh();
        $this->assertEquals(InvoiceStatus::PAID, $invoice->status);
        $this->assertEquals(1000000, $invoice->paid_amount);
        $this->assertCount(2, $invoice->payments);
    }

    public function test_validation_fails_without_required_fields(): void
    {
        $response = $this->post(route('payments.store'), []);
        $response->assertSessionHasErrors(['invoice_id', 'payment_date', 'amount', 'payment_method']);
    }

    public function test_can_view_payment_detail(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payments.show', $payment));
        $response->assertStatus(200);
        $response->assertSee($payment->payment_number);
    }

    public function test_can_update_payment(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->put(route('payments.update', $payment), [
            'invoice_id' => $payment->invoice_id,
            'payment_date' => '2026-07-21',
            'amount' => 500000,
            'payment_method' => PaymentMethod::TRANSFER->value,
            'reference_number' => 'TRF-002',
        ]);

        $response->assertRedirect(route('payments.index'));
        $payment->refresh();
        $this->assertEquals(500000, $payment->amount);
    }
}

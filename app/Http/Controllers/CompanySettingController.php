<?php

namespace App\Http\Controllers;

use App\Services\CompanySettingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    public function __construct(
        private CompanySettingService $companySetting,
    ) {}

    public function index(): View
    {
        $settings = $this->companySetting->getAll();

        $defaults = [
            'company_name' => '',
            'npwp' => '',
            'address' => '',
            'phone' => '',
            'email' => '',
            'bank_name' => '',
            'bank_account' => '',
            'bank_branch' => '',
            'signature_name' => '',
        ];

        $settings = array_merge($defaults, $settings);

        return view('company-settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => 'nullable|string|max:255',
            'npwp' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:100',
            'bank_branch' => 'nullable|string|max:255',
            'signature_name' => 'nullable|string|max:255',
        ]);

        $this->companySetting->saveAll($validated);

        return redirect()->route('company-settings.index')
            ->with('success', 'Pengaturan perusahaan berhasil disimpan.');
    }
}

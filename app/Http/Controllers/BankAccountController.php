<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('seller');
    }

    public function index()
    {
        $bankAccounts = auth()->user()->bankAccounts()->get();

        return view('seller.bank-accounts.index', compact('bankAccounts'));
    }

    public function create()
    {
        return view('seller.bank-accounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|unique:bank_accounts|string|max:30',
            'account_holder' => 'required|string|max:100',
            'is_primary' => 'boolean',
        ]);

        if ($validated['is_primary'] ?? false) {
            auth()->user()->bankAccounts()->update(['is_primary' => false]);
        }

        auth()->user()->bankAccounts()->create($validated);

        return redirect()->route('seller.bank-accounts.index')
            ->with('success', 'Rekening bank berhasil ditambahkan');
    }

    public function edit(BankAccount $bankAccount)
    {
        $this->authorize('update', $bankAccount);

        return view('seller.bank-accounts.edit', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $this->authorize('update', $bankAccount);

        $validated = $request->validate([
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:30|unique:bank_accounts,account_number,' . $bankAccount->id,
            'account_holder' => 'required|string|max:100',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ]);

        if ($validated['is_primary'] ?? false) {
            auth()->user()->bankAccounts()
                ->where('id', '!=', $bankAccount->id)
                ->update(['is_primary' => false]);
        }

        $bankAccount->update($validated);

        return redirect()->route('seller.bank-accounts.index')
            ->with('success', 'Rekening bank berhasil diperbarui');
    }

    public function destroy(BankAccount $bankAccount)
    {
        $this->authorize('delete', $bankAccount);

        $bankAccount->delete();

        return back()->with('success', 'Rekening bank berhasil dihapus');
    }
}

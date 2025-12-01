<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    /**
     * Tampilkan halaman invoice untuk transaksi tertentu.
     */
    public function show(Transaction $transaction)
    {
        $user = Auth::user();

        // Hanya pemilik transaksi atau admin yang boleh melihat
        $isAdmin = method_exists($user, 'isAdmin') ? $user->isAdmin() : (($user->role ?? 'user') === 'admin');
        if (!$isAdmin && $transaction->user_id !== $user->id) {
            abort(403, 'Anda tidak berhak mengakses invoice ini.');
        }

        $transaction->load(['user', 'items']);

        return view('invoice.show', [
            'transaction' => $transaction,
        ]);
    }
}
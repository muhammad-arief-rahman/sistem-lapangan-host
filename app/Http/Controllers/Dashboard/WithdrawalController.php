<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $withdrawals = Withdrawal::getWithdrawalData();
        $withdrawMethods = WithdrawMethod::all()->groupBy('category');

        $cardData = (object) [
            'totalWithdrawals' => $withdrawals->count(),
            'balance' => $user->balance,
            'pendingWithdrawalAmount' => $withdrawals->where('status', 'pending')->sum('amount'),
            'completedWithdrawal' => $withdrawals->where('status', 'completed')->count(),
        ];

        return view('pages.dashboard.withdrawal.index', compact('withdrawals', 'cardData', 'withdrawMethods'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:10000',
            'account_type' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'account_name' => 'required|string|max:255',

        ], [
            'amount.required' => 'Jumlah penarikan tidak boleh kosong.',
            'amount.integer' => 'Jumlah penarikan harus berupa angka.',
            'amount.min' => 'Jumlah penarikan minimal adalah Rp 10.000.',
            'account_type.required' => 'Jenis akun tidak boleh kosong.',
            'account_number.required' => 'Nomor akun tidak boleh kosong.',
            'account_name.required' => 'Nama pemilik akun tidak boleh kosong.',
            'account_type.string' => 'Jenis akun harus berupa teks.',
            'account_number.string' => 'Nomor akun harus berupa teks.',
            'account_name.string' => 'Nama pemilik akun harus berupa teks.',
        ]);

        $user = auth()->user();

        if ($user->balance < $request->amount) {
            return redirect()->back()->withErrors(['amount' => 'Saldo tidak cukup untuk melakukan penarikan.']);
        }

        DB::beginTransaction();

        try {
            $lockedUser = User::lockForUpdate()->find($user->id);

            // Check if the user has sufficient balance
            if ($lockedUser->balance < $request->amount) {
                DB::rollBack();
                return redirect()->back()->withErrors(['amount' => 'Saldo tidak cukup untuk melakukan penarikan.']);
            }

            // Check for pending withdrawals
            $pendingWithdrawals = $lockedUser->withdrawals()->where('status', 'pending')->sum('amount');

            $availableBalance = $lockedUser->balance - $pendingWithdrawals;

            if ($availableBalance < $request->amount) {
                DB::rollBack();
                return redirect()->back()->withErrors(['amount' => 'Anda memiliki penarikan yang sedang diproses. Saldo tersedia: ' . format_rp($availableBalance)]);
            }

            $withdrawal = $user->withdrawals()->create([
                'amount' => $request->amount,
                'account_type' => $request->account_type,
                'account_number' => $request->account_number,
                'account_name' => $request->account_name,
            ]);

            // Notify the admin
            User::where('role', 'super_admin')->get()->map(function ($admin) use ($withdrawal) {
                $admin->notify(new \App\Notifications\Database\UserWithdrawal($withdrawal));
                $admin->notify(new \App\Notifications\Mail\UserWithdrawal($withdrawal));
            });

            // Create mutations
            $user->mutations()->create([
                'source' => 'Penarikan Dana',
                'amount' => -$request->amount,
                'description' => 'Penarikan dana oleh pengguna #' . $user->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', 'Gagal membuat penarikan: ' . $e->getMessage());
        }

        return redirect()->back()->with('alert', [
            'title' => 'Berhasil!',
            'message' => 'Penarikan berhasil dibuat. Silakan tunggu proses verifikasi.',
            'type' => 'success',
        ]);
    }

    public function process(Request $request)
    {
        $request->validate([
            'withdrawal_id' => 'required|exists:withdrawals,id',
            'notes' => 'nullable|string|max:255',
            'proof' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'withdrawal_id.required' => 'ID penarikan tidak boleh kosong.',
            'withdrawal_id.exists' => 'Penarikan tidak ditemukan.',
            'notes.string' => 'Catatan harus berupa teks.',
            'notes.max' => 'Catatan tidak boleh lebih dari 255 karakter.',
            'proof.image' => 'Bukti transfer harus berupa gambar.',
            'proof.mimes' => 'Bukti transfer harus berupa file dengan format jpg, jpeg, atau png.',
            'proof.max' => 'Bukti transfer tidak boleh lebih dari 2 MB.',
        ]);

        DB::beginTransaction();

        try {
            $withdrawal = Withdrawal::with('user')->find($request->withdrawal_id);

            $image = store_image($request->file('proof'), 'withdrawals');

            $withdrawal->update([
                'status' => 'completed',
                'notes' => $request->notes,
                'approved_at' => now(),
                'transfer_proof' => $image,
            ]);

            // Notify the user
            $withdrawal->user->notify(new \App\Notifications\Database\WithdrawalApproved($withdrawal));
            $withdrawal->user->notify(new \App\Notifications\Mail\WithdrawalApproved($withdrawal));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', 'Gagal memproses penarikan: ' . $e->getMessage());
        }

        return redirect()->back()->with('toast', 'Penarikan berhasil diproses. Notifikasi telah dikirim ke pengguna.');
    }

    public function reject(Request $request)
    {
        $request->validate([
            'withdrawal_id' => 'required|exists:withdrawals,id',
            'notes' => 'nullable|string|max:255',
        ], [
            'withdrawal_id.required' => 'ID penarikan tidak boleh kosong.',
            'withdrawal_id.exists' => 'Penarikan tidak ditemukan.',
            'notes.string' => 'Catatan harus berupa teks.',
            'notes.max' => 'Catatan tidak boleh lebih dari 255 karakter.',
        ]);

        DB::beginTransaction();

        try {
            $withdrawal = Withdrawal::find($request->withdrawal_id);

            $withdrawal->update([
                'status' => 'failed',
                'notes' => $request->notes,
            ]);

            $withdrawal->refresh();

            // Notify the user
            $withdrawal->user->notify(new \App\Notifications\Database\WithdrawalRejected($withdrawal));
            $withdrawal->user->notify(new \App\Notifications\Mail\WithdrawalRejected($withdrawal));

            // Refund the amount to the user's balance
            $withdrawal->user->mutations()->create([
                'source' => 'Pengembalian Penarikan',
                'amount' => $withdrawal->amount,
                'description' => 'Pengembalian penarikan yang ditolak #' . $withdrawal->id,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('toast', 'Gagal menolak penarikan: ' . $e->getMessage());
        }

        return redirect()->back()->with('toast', 'Penarikan berhasil ditolak. Notifikasi telah dikirim ke pengguna.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\FinanceEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function index(Request $request): View
    {
        $groupId = $request->user()->group_id;
        $entries = FinanceEntry::where('group_id', $groupId)
            ->orderByDesc('date')->orderByDesc('id')->get();

        return view('keuangan', [
            'entries' => $entries,
            'balance' => FinanceEntry::balance($groupId),
            'totalIn' => (int) $entries->where('type', 'masuk')->sum('amount'),
            'totalOut' => (int) $entries->where('type', 'keluar')->sum('amount'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('manage-finance');

        $data = $request->validate([
            'date' => ['required', 'date'],
            'type' => ['required', Rule::in(['masuk', 'keluar'])],
            'category' => ['required', 'string', 'max:100'],
            'amount' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);
        $data['user_id'] = $request->user()->id;
        $data['group_id'] = $request->user()->group_id;

        FinanceEntry::create($data);

        return redirect()->route('keuangan.index')->with('ok', 'Transaksi dicatat.');
    }

    public function destroy(Request $request, FinanceEntry $finance): RedirectResponse
    {
        Gate::authorize('manage-finance');
        abort_unless($finance->group_id === $request->user()->group_id, 404);
        $finance->delete();

        return redirect()->route('keuangan.index')->with('ok', 'Transaksi dihapus.');
    }
}

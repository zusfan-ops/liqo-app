<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\FinanceEntry;
use App\Models\Meeting;
use App\Models\User;
use App\Services\PrayerTimes;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $group = $request->user()->group;
        $prayer = PrayerTimes::today($group->city, $group->country);

        return view('beranda', [
            'group' => $group,
            'upcoming' => Meeting::where('group_id', $group->id)
                ->whereDate('date', '>=', today())->orderBy('date')->orderBy('time')->first(),
            'balance' => FinanceEntry::balance($group->id),
            'pinned' => Announcement::where('group_id', $group->id)
                ->orderByDesc('pinned')->latest()->first(),
            'prayer' => $prayer,
            'nextPrayer' => $prayer ? PrayerTimes::next($prayer['timings']) : null,
            'pendingCount' => Gate::allows('manage-members')
                ? User::where('group_id', $group->id)->where('status', 'pending')->count()
                : 0,
        ]);
    }
}

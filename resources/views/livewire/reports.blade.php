<?php

use App\Models\ActivityUpdateLog;
use function Livewire\Volt\{state, computed, layout};

layout('layouts.app');

state([
    'from' => now()->subDays(7)->toDateString(),
    'to' => now()->toDateString(),
]);

$logs = computed(function () {
    return ActivityUpdateLog::with(['entry.activity', 'updatedBy'])
        ->whereBetween('created_at', ["{$this->from} 00:00:00", "{$this->to} 23:59:59"])
        ->latest('created_at')
        ->get();
});

$completionsByDay = computed(function () {
    return $this->logs
        ->where('new_status', 'done')
        ->groupBy(fn ($log) => $log->created_at->toDateString())
        ->map->count();
});

?>

<div class="py-8 bg-[#f0f0f0] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2">
            <div>
                <span class="inline-flex items-center gap-1.5 rounded-full bg-indigo-50 border border-indigo-200/80 px-3 py-1 text-[11px] font-extrabold text-indigo-700">
                    <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                    Historical Analytics & Audit Logs
                </span>
                <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-[#141414] tracking-tight">Activity Reports</h1>
            </div>

            <!-- Date Range Controls Bento Card -->
            <div class="rounded-[22px] border-[1.6px] border-white/90 bg-white p-4 shadow-[0_4px_20px_rgba(24,30,45,0.045)] flex flex-wrap items-center gap-3">
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">From</label>
                    <input type="date"
                           wire:model.live="from"
                           class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-800 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                </div>
                <div>
                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">To</label>
                    <input type="date"
                           wire:model.live="to"
                           class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-800 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                </div>
            </div>
        </div>

        <!-- Completions Bar Chart Bento Card -->
        <div class="rounded-[22px] border-[1.6px] border-white/90 bg-white p-6 shadow-[0_4px_20px_rgba(24,30,45,0.045)] space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                    <h2 class="text-base font-extrabold text-slate-900">Completions Per Day</h2>
                </div>
                <span class="text-xs font-bold text-slate-400">Range: {{ $this->from }} to {{ $this->to }}</span>
            </div>

            <div class="space-y-2.5 pt-2">
                @forelse ($this->completionsByDay as $date => $count)
                    <div class="flex items-center gap-4">
                        <span class="w-28 text-xs font-bold text-slate-600 shrink-0">{{ \Carbon\Carbon::parse($date)->format('D, M j, Y') }}</span>
                        <div class="flex-grow bg-slate-100 rounded-full h-4 overflow-hidden p-0.5 max-w-md">
                            <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full transition-all duration-500 shadow-2xs"
                                 style="width: {{ min($count * 20, 100) }}%"></div>
                        </div>
                        <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-0.5 text-xs font-extrabold text-emerald-700">
                            {{ $count }} completed
                        </span>
                    </div>
                @empty
                    <div class="py-6 text-center text-xs font-medium text-slate-500">
                        No activity completions recorded in this date range.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Activity History Log Table Bento Card -->
        <div class="rounded-[22px] border-[1.6px] border-white/90 bg-white shadow-[0_4px_20px_rgba(24,30,45,0.045)] overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                <div class="flex items-center gap-2.5">
                    <span class="h-3 w-3 rounded-full bg-indigo-500"></span>
                    <h2 class="text-base font-extrabold text-slate-900">Audit Trail History Logs</h2>
                </div>
                <span class="rounded-full bg-indigo-100/80 px-3 py-1 text-xs font-extrabold text-indigo-900">
                    {{ $this->logs->count() }} log entries
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/30 text-[10px] uppercase font-extrabold tracking-wider text-slate-400">
                            <th class="px-6 py-3.5">Activity</th>
                            <th class="px-6 py-3.5">Status Transition</th>
                            <th class="px-6 py-3.5">Updated By</th>
                            <th class="px-6 py-3.5">Remark / Note</th>
                            <th class="px-6 py-3.5 text-right">Timestamp</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse ($this->logs as $log)
                            <tr class="hover:bg-indigo-50/30 transition">
                                <td class="px-6 py-4">
                                    <p class="font-extrabold text-slate-900 text-sm">{{ $log->entry->activity->name ?? 'Activity' }}</p>
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-[10px] font-semibold text-slate-600 mt-1">
                                        {{ $log->entry->activity->category ?? 'General' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                            {{ $log->old_status }}
                                        </span>
                                        <span class="text-slate-400 font-bold">&rarr;</span>
                                        <span class="rounded-full px-2.5 py-0.5 text-xs font-extrabold {{ $log->new_status === 'done' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                            {{ $log->new_status }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-indigo-600 text-white text-[10px] font-extrabold flex items-center justify-center">
                                            {{ strtoupper(substr($log->updatedBy->name ?? 'U', 0, 1)) }}
                                        </span>
                                        <span class="font-bold text-slate-800 text-xs">{{ $log->updatedBy->name ?? 'Unknown User' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-xs text-slate-600 italic max-w-xs truncate">
                                        {{ $log->remark ? '"'.$log->remark.'"' : 'No remark provided' }}
                                    </p>
                                    @if($log->actual_value !== null)
                                        <span class="text-[10px] font-bold text-indigo-600">Value: {{ $log->actual_value }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-xs text-slate-500 font-medium">
                                        {{ $log->created_at->format('M j, Y — g:ia') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-xs font-medium text-slate-500">
                                    No activity update logs found for the selected date range.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

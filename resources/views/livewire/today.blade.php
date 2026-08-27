<?php

use App\Models\ActivityUpdateLog;
use App\Models\DailyActivityEntry;
use function Livewire\Volt\{state, computed, layout};

layout('layouts.app');

state(['selectedEntry' => null, 'remark' => '', 'actualValue' => null]);

$entries = computed(function () {
    return DailyActivityEntry::with('activity')->whereDate('date', today())->get();
});

$selectEntry = function (int $entryId) {
    $this->selectedEntry = DailyActivityEntry::with('activity')->find($entryId);
    $this->remark = '';
    $this->actualValue = $this->selectedEntry->actual_value;
};

$save = function (string $status) {
    $entry = $this->selectedEntry;

    if (! $entry) {
        return;
    }

    \DB::transaction(function () use ($entry, $status) {
        ActivityUpdateLog::create([
            'daily_activity_entry_id' => $entry->id,
            'updated_by' => auth()->id(),
            'old_status' => $entry->status,
            'new_status' => $status,
            'remark' => $this->remark,
            'actual_value' => $this->actualValue,
        ]);

        $entry->update([
            'status' => $status,
            'actual_value' => $this->actualValue ?? $entry->actual_value,
            'variance' => ($this->actualValue !== null && $entry->expected_value !== null)
                ? $this->actualValue - $entry->expected_value
                : $entry->variance,
        ]);
    });

    $this->selectedEntry = null;
    unset($this->entries);
};

?>

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 border-b border-gray-200 pb-5">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-indigo-600">Daily Operations</p>
                <h1 class="mt-1 text-3xl font-extrabold text-gray-900 tracking-tight">Today's Tracker</h1>
            </div>

            <div class="inline-flex items-center gap-2 rounded-xl border border-gray-200 bg-white px-4 py-2 shadow-sm self-start md:self-auto">
                <svg class="h-5 w-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <div class="text-left">
                    <p class="text-[10px] font-bold uppercase tracking-wider text-gray-400">Date</p>
                    <p class="text-sm font-semibold text-gray-800">{{ today()->format('D, M j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Summary Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Pending Card -->
            <div class="relative overflow-hidden rounded-xl border border-amber-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-amber-600">Pending</p>
                        <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ $this->entries->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="rounded-xl bg-amber-50 p-3 text-amber-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Completed Card -->
            <div class="relative overflow-hidden rounded-xl border border-emerald-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-emerald-600">Completed</p>
                        <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ $this->entries->where('status', 'done')->count() }}</p>
                    </div>
                    <div class="rounded-xl bg-emerald-50 p-3 text-emerald-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Card -->
            <div class="relative overflow-hidden rounded-xl border border-indigo-200 bg-white p-5 shadow-sm transition hover:shadow-md">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-indigo-600">Total Scheduled</p>
                        <p class="mt-2 text-3xl font-extrabold text-gray-900">{{ $this->entries->count() }}</p>
                    </div>
                    <div class="rounded-xl bg-indigo-50 p-3 text-indigo-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Items Section -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 px-5 py-4">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                    <h2 class="text-base font-bold text-gray-900">Pending Activities</h2>
                </div>
                <span class="rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-semibold text-amber-800">
                    {{ $this->entries->where('status', 'pending')->count() }} items
                </span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($this->entries->where('status', 'pending') as $entry)
                    <button wire:click="selectEntry({{ $entry->id }})"
                            type="button"
                            class="group flex w-full items-center justify-between p-4 sm:p-5 text-left transition hover:bg-indigo-50/30 focus:outline-none">
                        <div class="space-y-1 pr-4">
                            <p class="text-sm font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                {{ $entry->activity->name }}
                            </p>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                                    {{ $entry->activity->category ?? 'General' }}
                                </span>
                                @if($entry->expected_value !== null)
                                    <span class="text-xs text-gray-400">
                                        Expected: <strong class="text-gray-600">{{ $entry->expected_value }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            @php
                                $type = strtolower($entry->activity->type ?? 'task');
                                $badgeClass = match($type) {
                                    'metric' => 'bg-purple-50 text-purple-700 border-purple-200',
                                    'checklist' => 'bg-blue-50 text-blue-700 border-blue-200',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200',
                                };
                            @endphp
                            <span class="rounded-lg border px-2.5 py-1 text-[11px] font-bold uppercase tracking-wider {{ $badgeClass }}">
                                {{ $entry->activity->type ?? 'task' }}
                            </span>
                            <span class="hidden sm:inline-flex items-center text-xs font-semibold text-indigo-600 group-hover:translate-x-0.5 transition-transform">
                                Action &rarr;
                            </span>
                        </div>
                    </button>
                @empty
                    <div class="px-5 py-10 text-center">
                        <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-500">All caught up! No pending activities remaining for today.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Completed Items Section -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
            <div class="flex items-center justify-between border-b border-gray-100 bg-gray-50/50 px-5 py-4">
                <div class="flex items-center gap-2">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    <h2 class="text-base font-bold text-gray-900">Completed Activities</h2>
                </div>
                <span class="rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800">
                    {{ $this->entries->where('status', 'done')->count() }} items
                </span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse ($this->entries->where('status', 'done') as $entry)
                    <div class="flex items-center justify-between p-4 sm:p-5 text-left">
                        <div class="space-y-1">
                            <p class="text-sm font-semibold text-gray-900 line-through text-gray-500">
                                {{ $entry->activity->name }}
                            </p>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-0.5 font-medium text-gray-600">
                                    {{ $entry->activity->category ?? 'General' }}
                                </span>
                                @if ($entry->actual_value !== null)
                                    <span class="font-medium text-emerald-700">
                                        Actual Value: {{ $entry->actual_value }}
                                        @if($entry->variance !== null)
                                            <span class="{{ $entry->variance >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                                (Variance: {{ $entry->variance >= 0 ? '+'.$entry->variance : $entry->variance }})
                                            </span>
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-700">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Done
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-gray-500">
                        No completed tasks recorded yet today.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Update Task Modal -->
    @if ($selectedEntry)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
             wire:click.self="$set('selectedEntry', null)">
            <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-2xl border border-gray-100 space-y-5 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-start justify-between border-b border-gray-100 pb-4">
                    <div>
                        <span class="rounded-md bg-indigo-50 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-indigo-700">
                            {{ $selectedEntry->activity->type ?? 'Task' }}
                        </span>
                        <h3 class="mt-1.5 text-xl font-bold text-gray-900">{{ $selectedEntry->activity->name }}</h3>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $selectedEntry->activity->category ?? 'General' }}</p>
                    </div>

                    <button type="button"
                            wire:click="$set('selectedEntry', null)"
                            class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @if ($selectedEntry->activity->type === 'metric')
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            Actual Value
                            @if($selectedEntry->expected_value !== null)
                                <span class="text-xs font-normal text-gray-500">(Target / Expected: {{ $selectedEntry->expected_value }})</span>
                            @endif
                        </label>
                        <input type="number" step="0.01" wire:model="actualValue"
                               placeholder="Enter actual number..."
                               class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Remark / Notes</label>
                    <textarea wire:model="remark" rows="3"
                              placeholder="Add optional notes or status updates..."
                              class="w-full rounded-lg border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500"></textarea>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-gray-100 pt-4">
                    <button type="button"
                            wire:click="$set('selectedEntry', null)"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition">
                        Cancel
                    </button>
                    <button type="button"
                            wire:click="save('pending')"
                            class="rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-2 text-sm font-semibold text-indigo-700 hover:bg-indigo-100 transition">
                        Save Note
                    </button>
                    <button type="button"
                            wire:click="save('done')"
                            class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition">
                        Mark Done
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
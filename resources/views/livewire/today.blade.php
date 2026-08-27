<?php

use App\Models\ActivityUpdateLog;
use App\Models\DailyActivityEntry;
use Masmerise\Toaster\Toaster;
use function Livewire\Volt\{state, computed, layout};

layout('layouts.app');

state([
    'selectedEntryId' => null,
    'remark' => '',
    'actualValue' => null,
]);

$entries = computed(function () {
    return DailyActivityEntry::with('activity')->whereDate('date', today())->get();
});

$selectedEntry = computed(function () {
    return $this->selectedEntryId
        ? DailyActivityEntry::with('activity')->find($this->selectedEntryId)
        : null;
});

$selectEntry = function (int $entryId) {
    $this->selectedEntryId = $entryId;
    $entry = DailyActivityEntry::find($entryId);
    $this->remark = '';
    $this->actualValue = $entry?->actual_value;
};

$closeModal = function () {
    $this->selectedEntryId = null;
    $this->remark = '';
    $this->actualValue = null;
};

$save = function (string $status) {
    if (! $this->selectedEntryId) {
        return;
    }

    $entry = DailyActivityEntry::with('activity')->find($this->selectedEntryId);

    if (! $entry) {
        $this->closeModal();
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

    $activityName = $entry->activity->name ?? 'Activity';
    if ($status === 'done') {
        Toaster::success("'{$activityName}' marked as completed!");
    } else {
        Toaster::info("Saved updates for '{$activityName}'.");
    }

    $this->selectedEntryId = null;
    $this->remark = '';
    $this->actualValue = null;
    unset($this->entries);
    unset($this->selectedEntry);
};

?>

<div class="py-8 bg-[#f0f0f0] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2">
            <div>
                <h1 class="mt-2 text-3xl sm:text-4xl font-extrabold text-[#141414] tracking-tight">Today's Activity</h1>
            </div>

            <div class="inline-flex items-center gap-3 rounded-full border-[1.6px] border-white/90 bg-white px-5 py-2.5 shadow-[0_4px_20px_rgba(24,30,45,0.045)] self-start md:self-auto">
                <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div class="text-left">
                    <p class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">Date</p>
                    <p class="text-xs font-bold text-slate-800">{{ today()->format('D, M j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Bento Stat Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <!-- Pending Card -->
            <div class="rounded-[22px] border-[1.6px] border-white/90 shadow-[0_4px_20px_rgba(24,30,45,0.045)] p-6 relative overflow-hidden flex items-center justify-between transition hover:scale-[1.01]"
                 style="background: radial-gradient(120% 140% at 92% 100%, rgba(255,236,246,.95) 0%, rgba(255,236,246,0) 62%), linear-gradient(135deg, #f9d9e9 0%, #fbdfec 55%, #fce6f1 100%);">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-wider text-pink-700">Pending</p>
                    <p class="mt-2 text-4xl font-extrabold text-[#0d0d0d] tracking-tight">{{ $this->entries->where('status', 'pending')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/80 border border-white/90 flex items-center justify-center text-pink-600 shadow-xs">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Completed Card -->
            <div class="rounded-[22px] border-[1.6px] border-white/90 shadow-[0_4px_20px_rgba(24,30,45,0.045)] p-6 relative overflow-hidden flex items-center justify-between transition hover:scale-[1.01]"
                 style="background: radial-gradient(90% 70% at 6% 0%, rgba(226,236,200,.9) 0%, rgba(226,236,200,0) 70%), linear-gradient(168deg, #e2ebc9 0%, #e9f0c4 48%, #f0f4b8 78%, #f3f5b0 100%);">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-wider text-[#4f7433]">Completed</p>
                    <p class="mt-2 text-4xl font-extrabold text-[#15201a] tracking-tight">{{ $this->entries->where('status', 'done')->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/80 border border-white/90 flex items-center justify-center text-[#4f7433] shadow-xs">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <!-- Total Card -->
            <div class="rounded-[22px] border-[1.6px] border-white/90 shadow-[0_4px_20px_rgba(24,30,45,0.045)] p-6 relative overflow-hidden flex items-center justify-between transition hover:scale-[1.01]"
                 style="background: linear-gradient(103deg, #eae9f5 0%, #e2e0f1 34%, #cfcdea 72%, #c2c0e6 100%);">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-wider text-indigo-800">Total Scheduled</p>
                    <p class="mt-2 text-4xl font-extrabold text-[#0d0d10] tracking-tight">{{ $this->entries->count() }}</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-white/80 border border-white/90 flex items-center justify-center text-indigo-700 shadow-xs">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Items Bento Card -->
        <div class="rounded-[22px] border-[1.6px] border-white/90 bg-white shadow-[0_4px_20px_rgba(24,30,45,0.045)] overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                <div class="flex items-center gap-2.5">
                    <span class="h-3 w-3 rounded-full bg-amber-400 animate-pulse"></span>
                    <h2 class="text-base font-extrabold text-slate-900">Pending Activities</h2>
                </div>
                <span class="rounded-full bg-amber-100/80 px-3 py-1 text-xs font-extrabold text-amber-900">
                    {{ $this->entries->where('status', 'pending')->count() }} items
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($this->entries->where('status', 'pending') as $entry)
                    <button wire:click="selectEntry({{ $entry->id }})"
                            type="button"
                            class="group flex w-full items-center justify-between p-5 text-left transition hover:bg-indigo-50/40 focus:outline-none">
                        <div class="space-y-1.5 pr-4">
                            <p class="text-sm font-extrabold text-slate-900 group-hover:text-indigo-600 transition-colors">
                                {{ $entry->activity->name }}
                            </p>
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                    {{ $entry->activity->category ?? 'General' }}
                                </span>
                                @if($entry->expected_value !== null)
                                    <span class="text-xs text-slate-400 font-medium">
                                        Target: <strong class="text-slate-700">{{ $entry->expected_value }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            @php
                                $type = strtolower($entry->activity->type ?? 'task');
                                $badgeStyle = match($type) {
                                    'metric' => 'bg-purple-50 text-purple-700 border-purple-200/80',
                                    'checklist' => 'bg-blue-50 text-blue-700 border-blue-200/80',
                                    default => 'bg-slate-50 text-slate-700 border-slate-200/80',
                                };
                            @endphp
                            <span class="rounded-full border px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider {{ $badgeStyle }}">
                                {{ $entry->activity->type ?? 'task' }}
                            </span>
                            <span class="hidden sm:inline-flex items-center rounded-full bg-indigo-50 px-3 py-1 text-xs font-extrabold text-indigo-600 group-hover:translate-x-1 transition-transform">
                                Update &rarr;
                            </span>
                        </div>
                    </button>
                @empty
                    <div class="px-6 py-12 text-center">
                        <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 mx-auto flex items-center justify-center mb-3">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <p class="text-sm font-extrabold text-slate-800">All caught up!</p>
                        <p class="text-xs text-slate-500 mt-1">No pending tasks remaining for today.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Completed Items Bento Card -->
        <div class="rounded-[22px] border-[1.6px] border-white/90 bg-white shadow-[0_4px_20px_rgba(24,30,45,0.045)] overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                <div class="flex items-center gap-2.5">
                    <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                    <h2 class="text-base font-extrabold text-slate-900">Completed Activities</h2>
                </div>
                <span class="rounded-full bg-emerald-100/80 px-3 py-1 text-xs font-extrabold text-emerald-800">
                    {{ $this->entries->where('status', 'done')->count() }} items
                </span>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($this->entries->where('status', 'done') as $entry)
                    <div class="flex items-center justify-between p-5 text-left">
                        <div class="space-y-1">
                            <p class="text-sm font-extrabold text-slate-400 line-through">
                                {{ $entry->activity->name }}
                            </p>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500 font-medium">
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 font-semibold text-slate-600">
                                    {{ $entry->activity->category ?? 'General' }}
                                </span>
                                @if ($entry->actual_value !== null)
                                    <span class="font-bold text-emerald-700">
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

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3.5 py-1 text-xs font-extrabold text-emerald-700">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Done
                        </span>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-xs font-medium text-slate-500">
                        No completed tasks recorded yet today.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Update Task Glassmorphic Modal -->
    @if ($this->selectedEntry)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4"
             wire:click.self="closeModal">
            <div class="w-full max-w-lg rounded-[24px] bg-white p-7 shadow-2xl border-[1.6px] border-white/90 space-y-5 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span class="rounded-full bg-indigo-50 px-3 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-indigo-700">
                            {{ $this->selectedEntry->activity->type ?? 'Task' }}
                        </span>
                        <h3 class="mt-2 text-xl font-extrabold text-slate-900">{{ $this->selectedEntry->activity->name }}</h3>
                        <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $this->selectedEntry->activity->category ?? 'General' }}</p>
                    </div>

                    <button type="button"
                            wire:click="closeModal"
                            class="rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                @if ($this->selectedEntry->activity->type === 'metric')
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                            Actual Value
                            @if($this->selectedEntry->expected_value !== null)
                                <span class="text-xs font-normal text-slate-400 normal-case">(Expected: {{ $this->selectedEntry->expected_value }})</span>
                            @endif
                        </label>
                        <input type="number" step="0.01" wire:model="actualValue"
                               placeholder="Enter actual value..."
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 shadow-xs focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Remark / Notes</label>
                    <textarea wire:model="remark" rows="3"
                              placeholder="Add optional notes or update remarks..."
                              class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 shadow-xs focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 transition"></textarea>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button"
                            wire:click="closeModal"
                            class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="button"
                            wire:click="save('pending')"
                            class="rounded-full border border-indigo-200 bg-indigo-50 px-5 py-2.5 text-xs font-extrabold text-indigo-700 hover:bg-indigo-100 transition">
                        Save Note
                    </button>
                    <button type="button"
                            wire:click="save('done')"
                            class="rounded-full bg-emerald-600 px-6 py-2.5 text-xs font-extrabold text-white shadow-md shadow-emerald-600/20 hover:bg-emerald-500 transition">
                        Mark Done
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
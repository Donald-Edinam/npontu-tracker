<?php

use App\Models\Activity;
use Masmerise\Toaster\Toaster;
use function Livewire\Volt\{state, computed, layout};

layout('layouts.app');

state([
    'showForm' => false,
    'editingId' => null,
    'name' => '',
    'description' => '',
    'type' => 'checklist',
    'category' => '',
]);

$activities = computed(fn () => Activity::latest()->get());

$create = function () {
    $this->reset(['editingId', 'name', 'description', 'type', 'category']);
    $this->showForm = true;
};

$edit = function (int $id) {
    $activity = Activity::findOrFail($id);
    $this->editingId = $activity->id;
    $this->name = $activity->name;
    $this->description = $activity->description ?? '';
    $this->type = $activity->type;
    $this->category = $activity->category ?? '';
    $this->showForm = true;
};

$closeForm = function () {
    $this->showForm = false;
    $this->reset(['editingId', 'name', 'description', 'type', 'category']);
};

$save = function () {
    $this->authorize($this->editingId ? 'update' : 'create', $this->editingId ? Activity::find($this->editingId) : Activity::class);

    $data = $this->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'type' => 'required|in:checklist,metric',
        'category' => 'nullable|string|max:255',
    ]);

    if ($this->editingId) {
        $activity = Activity::find($this->editingId);
        $activity->update($data);
        Toaster::info("Updated activity '{$activity->name}'.");
    } else {
        $activity = Activity::create([...$data, 'created_by' => auth()->id(), 'is_active' => true]);
        Toaster::success("Created new activity '{$activity->name}'.");
    }

    $this->closeForm();
    unset($this->activities);
};

$toggleActive = function (int $id) {
    $activity = Activity::findOrFail($id);
    $this->authorize('update', $activity);
    $activity->update(['is_active' => ! $activity->is_active]);

    $statusLabel = $activity->is_active ? 'activated' : 'deactivated';
    Toaster::info("'{$activity->name}' has been {$statusLabel}.");

    unset($this->activities);
};

?>

<div class="py-8 bg-[#f0f0f0] min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-2">
            <button wire:click="create"
                    type="button"
                    class="inline-flex items-center gap-2 rounded-full bg-slate-900 px-6 py-3 text-xs font-extrabold text-white shadow-md hover:bg-slate-800 transition self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                New Activity
            </button>
        </div>

        <!-- Activity Table Bento Card -->
        <div class="rounded-[22px] border-[1.6px] border-white/90 bg-white shadow-[0_4px_20px_rgba(24,30,45,0.045)] overflow-hidden">
            <div class="flex items-center justify-between border-b border-slate-100 bg-slate-50/60 px-6 py-4">
                <div class="flex items-center gap-2.5">
                    <span class="h-3 w-3 rounded-full bg-indigo-500"></span>
                    <h2 class="text-base font-extrabold text-slate-900">Configured Activities</h2>
                </div>
                <span class="rounded-full bg-indigo-100/80 px-3 py-1 text-xs font-extrabold text-indigo-900">
                    {{ $this->activities->count() }} items
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/30 text-[10px] uppercase font-extrabold tracking-wider text-slate-400">
                            <th class="px-6 py-3.5">Activity Name</th>
                            <th class="px-6 py-3.5">Type</th>
                            <th class="px-6 py-3.5">Category</th>
                            <th class="px-6 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                        @forelse ($this->activities as $activity)
                            <tr class="hover:bg-indigo-50/30 transition">
                                <td class="px-6 py-4">
                                    <p class="font-extrabold text-slate-900 text-sm">{{ $activity->name }}</p>
                                    @if($activity->description)
                                        <p class="text-xs text-slate-400 mt-0.5 max-w-md truncate">{{ $activity->description }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $type = strtolower($activity->type ?? 'checklist');
                                        $badgeStyle = match($type) {
                                            'metric' => 'bg-purple-50 text-purple-700 border-purple-200/80',
                                            'checklist' => 'bg-blue-50 text-blue-700 border-blue-200/80',
                                            default => 'bg-slate-50 text-slate-700 border-slate-200/80',
                                        };
                                    @endphp
                                    <span class="rounded-full border px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider {{ $badgeStyle }}">
                                        {{ $activity->type }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                        {{ $activity->category ?? 'General' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <button wire:click="toggleActive({{ $activity->id }})"
                                            type="button"
                                            class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-extrabold transition shadow-2xs {{ $activity->is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                                        <span class="h-2 w-2 rounded-full {{ $activity->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $activity->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="edit({{ $activity->id }})"
                                            type="button"
                                            class="rounded-full bg-indigo-50 px-3.5 py-1 text-xs font-extrabold text-indigo-600 hover:bg-indigo-100 transition">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-xs font-medium text-slate-500">
                                    No activities defined in the catalog yet. Click "+ New Activity" to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create/Edit Activity Glassmorphic Modal -->
    @if ($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4"
             wire:click.self="closeForm">
            <div class="w-full max-w-lg rounded-[24px] bg-white p-7 shadow-2xl border-[1.6px] border-white/90 space-y-5 animate-in fade-in zoom-in-95 duration-150">
                <div class="flex items-start justify-between border-b border-slate-100 pb-4">
                    <div>
                        <span class="rounded-full bg-indigo-50 px-3 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-indigo-700">
                            {{ $editingId ? 'Catalog Edit' : 'New Catalog Entry' }}
                        </span>
                        <h3 class="mt-2 text-xl font-extrabold text-slate-900">{{ $editingId ? 'Edit Activity' : 'Create New Activity' }}</h3>
                    </div>

                    <button type="button"
                            wire:click="closeForm"
                            class="rounded-full p-2 text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Activity Name</label>
                        <input type="text"
                               wire:model="name"
                               placeholder="e.g. Daily SMS count in comparison to logs"
                               class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 shadow-xs focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                        @error('name') <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Type</label>
                            <select wire:model="type"
                                    class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 shadow-xs focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                                <option value="checklist">Checklist</option>
                                <option value="metric">Metric (Quantitative number)</option>
                            </select>
                            @error('type') <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Category</label>
                            <input type="text"
                                   wire:model="category"
                                   placeholder="e.g. Messaging, Payments"
                                   class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 shadow-xs focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                            @error('category') <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">Description (Optional)</label>
                        <textarea wire:model="description"
                                  rows="3"
                                  placeholder="Describe the operational purpose of this activity..."
                                  class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-900 shadow-xs focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-1 focus:ring-indigo-500 transition"></textarea>
                        @error('description') <p class="text-xs font-bold text-rose-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-100 pt-5">
                    <button type="button"
                            wire:click="closeForm"
                            class="rounded-full border border-slate-200 bg-white px-5 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 transition">
                        Cancel
                    </button>
                    <button type="button"
                            wire:click="save"
                            class="rounded-full bg-slate-900 px-6 py-2.5 text-xs font-extrabold text-white shadow-md hover:bg-slate-800 transition">
                        Save Activity
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-extrabold text-xl text-slate-900 leading-tight tracking-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('today') }}" class="rounded-full bg-indigo-600 px-4 py-2 text-xs font-extrabold text-white shadow-xs hover:bg-indigo-500 transition">
                Go to Today's Tracker &rarr;
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-[#f0f0f0]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Welcome Bento Hero Card -->
            <div class="rounded-[22px] border-[1.6px] border-white/90 p-8 shadow-[0_4px_20px_rgba(24,30,45,0.045)] relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-6"
                 style="background: radial-gradient(90% 70% at 6% 0%, rgba(226,236,200,.9) 0%, rgba(226,236,200,0) 70%), linear-gradient(168deg, #e2ebc9 0%, #e9f0c4 48%, #f0f4b8 78%, #f3f5b0 100%);">
                <div class="space-y-2 max-w-xl">
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-white/80 border border-white/90 px-3.5 py-1 text-xs font-extrabold text-[#4f7433]">
                        Welcome back, {{ auth()->user()->name }} 👋
                    </span>
                    <h1 class="text-3xl font-extrabold text-[#15201a] tracking-tight">Operational Overview</h1>
                    <p class="text-sm font-medium text-[#1e2a1b] leading-relaxed">
                        Track scheduled tasks, manage metric updates, and review daily progress across your operations team.
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-3 shrink-0">
                    <a href="{{ route('today') }}"
                       class="rounded-full bg-[#15201a] px-6 py-3 text-xs font-extrabold text-white hover:bg-[#25382e] transition shadow-md">
                        Manage Today's Activities
                    </a>
                </div>
            </div>

            <!-- Bento Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Card 1 -->
                <div class="rounded-[22px] border-[1.6px] border-white/90 bg-white p-6 shadow-[0_4px_20px_rgba(24,30,45,0.045)] space-y-4">
                    <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-extrabold text-sm">
                        01
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Daily Operations</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Review pending checklists and metric entries assigned for today.</p>
                    </div>
                    <a href="{{ route('today') }}" class="inline-flex items-center text-xs font-extrabold text-indigo-600 hover:text-indigo-700">
                        View Activities &rarr;
                    </a>
                </div>

                <!-- Card 2 -->
                <div class="rounded-[22px] border-[1.6px] border-white/90 bg-white p-6 shadow-[0_4px_20px_rgba(24,30,45,0.045)] space-y-4">
                    <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-extrabold text-sm">
                        02
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Variance Calculation</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Automatic performance metric variance calculation against expected targets.</p>
                    </div>
                    <span class="inline-flex items-center text-xs font-extrabold text-emerald-600">
                        Automated Tracking
                    </span>
                </div>

                <!-- Card 3 -->
                <div class="rounded-[22px] border-[1.6px] border-white/90 bg-white p-6 shadow-[0_4px_20px_rgba(24,30,45,0.045)] space-y-4">
                    <div class="w-10 h-10 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center font-extrabold text-sm">
                        03
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Audit History</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Full audit log tracking user changes, status transitions, and custom remarks.</p>
                    </div>
                    <span class="inline-flex items-center text-xs font-extrabold text-purple-600">
                        Complete Transparency
                    </span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <title>Automate your work — Npontu Activity Tracker</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-[#141414] bg-[#f0f0f0] min-h-screen selection:bg-indigo-500 selection:text-white">
        <!-- Top Navigation -->
        <header class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <x-application-logo class="h-10 w-10 transition-transform group-hover:scale-105" />
                <div>
                    <span class="text-xl font-extrabold text-gray-900 tracking-tight">Npontu<span class="text-indigo-600">Tracker</span></span>
                    <span class="block text-[10px] uppercase tracking-widest text-slate-500 font-bold">Activity Management</span>
                </div>
            </a>

            <nav class="flex items-center gap-3">
                @auth
                    <a href="{{ route('today') }}" class="rounded-full bg-slate-900 px-5 py-2.5 text-xs font-extrabold text-white shadow-sm hover:bg-slate-800 transition">
                        Today's Activity &rarr;
                    </a>
                    <a href="{{ route('dashboard') }}" class="rounded-full border border-slate-300 bg-white px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="rounded-full border border-slate-300 bg-white px-5 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 transition">
                        Log in
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="rounded-full bg-indigo-600 px-5 py-2.5 text-xs font-extrabold text-white shadow-md shadow-indigo-600/20 hover:bg-indigo-500 transition">
                            Get Started
                        </a>
                    @endif
                @endauth
            </nav>
        </header>

        <!-- Bento Grid Mosaic Section -->
        <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 pb-16 space-y-8">
            <!-- Hero Subheader -->
            <div class="text-center max-w-2xl mx-auto space-y-3">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight">
                    Automate your work <br/><span class="text-indigo-600">Focus on what matters</span>
                </h1>
                <p class="text-sm sm:text-base text-slate-600 font-medium">
                    Streamline team tasks, monitor operational performance metrics, and calculate variance automatically in one clean workspace.
                </p>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <!-- CARD 1: NOTIFICATION (.notif) -->
                <div class="md:col-span-4 rounded-[22px] border-[1.6px] border-white/90 shadow-[0_4px_20px_rgba(24,30,45,0.045)] relative overflow-hidden p-6 flex flex-col justify-between"
                     style="background: radial-gradient(120% 140% at 92% 100%, rgba(255,236,246,.95) 0%, rgba(255,236,246,0) 62%), linear-gradient(135deg, #f9d9e9 0%, #fbdfec 55%, #fce6f1 100%);">
                    <div class="space-y-1">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-pink-700/70">Real-Time Alerts</span>
                        <h3 class="text-xl font-extrabold text-[#0d0d0d]">Automation Notifications</h3>
                    </div>

                    <!-- Toast Stack Component -->
                    <div class="relative mt-8 py-2">
                        <!-- Ledge behind toast -->
                        <div class="absolute left-4 right-4 top-4 h-12 rounded-[14px] shadow-sm"
                             style="background: linear-gradient(100deg, #e6e6e6 0%, #e6e3e2 42%, #e5d6d6 74%, #e4cdcf 100%);"></div>

                        <!-- Toast Foreground Card -->
                        <div class="relative z-10 rounded-[16px] p-4 flex items-center gap-3 shadow-[0_6px_20px_rgba(122,86,106,.15)]"
                             style="background: linear-gradient(105deg, #ffffff 34%, #fdeee5 78%, #fce8dd 100%);">
                            <!-- Sparkle Icon -->
                            <div class="w-9 h-9 rounded-full bg-slate-900 text-white flex items-center justify-center shrink-0 shadow-xs">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
                                </svg>
                            </div>
                            <div class="flex-grow min-w-0">
                                <p class="text-xs font-extrabold text-[#0d0d0d] leading-tight">Automation completed!</p>
                                <p class="text-[11px] font-normal text-[#2b2b2b] truncate mt-0.5">Weekly client report sent automatically</p>
                            </div>
                            <span class="text-[10px] font-semibold text-[#8b8489] self-start shrink-0">2:34 PM</span>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: AUTOMATE YOUR WORK HERO (.automate) -->
                <div class="md:col-span-8 rounded-[22px] border-[1.6px] border-white/90 shadow-[0_4px_20px_rgba(24,30,45,0.045)] relative overflow-hidden p-8 flex flex-col justify-between min-h-[320px]"
                     style="background: radial-gradient(90% 70% at 6% 0%, rgba(226,236,200,.9) 0%, rgba(226,236,200,0) 70%), linear-gradient(168deg, #e2ebc9 0%, #e9f0c4 48%, #f0f4b8 78%, #f3f5b0 100%);">
                    <div class="space-y-2 z-10 max-w-lg">
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-[#15201a] tracking-tight leading-tight">
                            <span class="text-[#5f8b3e]">Automate</span> your work.<br/>Focus on what matters.
                        </h2>
                        <p class="text-sm font-medium text-[#1e2a1b] leading-relaxed">
                            AI-powered activity workflows and daily checklist tracking that save operational teams hours every week.
                        </p>
                    </div>

                    <!-- Floating Badges & Window Mockups -->
                    <div class="mt-8 flex flex-wrap items-center gap-4 z-10">
                        <div class="inline-flex items-center gap-2.5 rounded-full bg-white px-4 py-2 shadow-sm border border-black/5 -rotate-1">
                            <span class="w-5 h-5 rounded-full bg-gradient-to-r from-emerald-200 to-green-300 flex items-center justify-center text-xs font-bold text-slate-800">&check;</span>
                            <span class="text-xs font-bold text-[#151515]">Workflow Automated</span>
                        </div>

                        <div class="inline-flex items-center gap-3 rounded-xl bg-white px-4 py-2.5 shadow-sm border border-black/5 -rotate-1">
                            <div class="flex items-center text-[#4f7433]">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 14.5L2 12L9.5 9.5L12 2Z"/>
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <div class="w-20 h-1.5 bg-[#d8d8d7] rounded-full"></div>
                                <div class="w-14 h-1.5 bg-[#e5e5e4] rounded-full"></div>
                            </div>
                        </div>

                        @auth
                            <a href="{{ route('today') }}" class="ml-auto rounded-full bg-[#15201a] px-6 py-2.5 text-xs font-extrabold text-white hover:bg-[#25382e] transition shadow-md">
                                Open Today's Activity &rarr;
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="ml-auto rounded-full bg-[#15201a] px-6 py-2.5 text-xs font-extrabold text-white hover:bg-[#25382e] transition shadow-md">
                                Get Started Free &rarr;
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- CARD 2: CONNECT YOUR TOOLS (.connect) -->
                <div class="md:col-span-5 rounded-[22px] border-[1.6px] border-white/90 shadow-[0_4px_20px_rgba(24,30,45,0.045)] relative overflow-hidden p-7 flex flex-col justify-between min-h-[300px]"
                     style="background: linear-gradient(180deg, #fcfdfd 0%, #f4f7f9 30%, #e2ebef 66%, #cedce4 100%);">
                    <div>
                        <h3 class="text-2xl font-extrabold text-[#0c0c0c] tracking-tight leading-tight">
                            Connect your<br/>Tools Now.
                        </h3>
                        <p class="text-xs font-medium text-[#1c1c1c] mt-2">120+ integrations available</p>
                    </div>

                    <!-- Integration Chips Stack -->
                    <div class="mt-6 space-y-2.5">
                        <div class="flex flex-wrap gap-2">
                            <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 bg-white/95 shadow-[0_0_0_2px_rgba(0,0,0,0.047)] text-xs font-semibold text-[#131313]">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                                Microsoft Teams
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 bg-white/95 shadow-[0_0_0_2px_rgba(0,0,0,0.047)] text-xs font-semibold text-[#131313]">
                                <span class="font-extrabold text-xs">N</span>
                                Notion
                            </span>
                        </div>

                        <div class="flex flex-wrap items-center gap-2 pl-3">
                            <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 bg-white/95 shadow-[0_0_0_2px_rgba(0,0,0,0.047)] text-xs font-semibold text-[#131313]">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0024 12c0-6.63-5.37-12-12-12z"/></svg>
                                GitHub
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 bg-white/95 shadow-[0_0_0_2px_rgba(0,0,0,0.047)] text-xs font-semibold text-[#131313]">
                                Google Drive
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full px-4 py-2 bg-white/95 shadow-[0_0_0_2px_rgba(0,0,0,0.047)] text-xs font-semibold text-[#131313]">
                                Figma
                            </span>
                        </div>
                    </div>
                </div>

                <!-- CARD 4: PRODUCTIVITY INSIGHTS (.insights) -->
                <div class="md:col-span-4 rounded-[22px] border-[1.6px] border-white/90 shadow-[0_4px_20px_rgba(24,30,45,0.045)] relative overflow-hidden p-6 flex flex-col justify-between min-h-[300px]"
                     style="background: radial-gradient(115% 70% at 22% 0%, #fdf2e5 0%, rgba(253,242,229,0) 68%), linear-gradient(180deg, #f9f1e8 0%, #f7efe6 100%);">
                    <div>
                        <span class="inline-flex items-center rounded-full bg-white/80 border border-white/90 px-3.5 py-1 text-[11px] font-extrabold text-[#111] shadow-xs">
                            Productivity Insights
                        </span>
                        <h3 class="text-3xl font-extrabold text-[#0b0b0b] mt-3 tracking-tight">48 hours</h3>
                        <p class="text-xs font-medium text-[#1d1d1d] mt-1">saved this week!</p>
                    </div>

                    <!-- Ascending Bar Chart Component -->
                    <div class="mt-6 flex items-end justify-between gap-1.5 h-36 pt-4 border-t border-black/5">
                        <div class="flex flex-col items-center gap-1.5 flex-1">
                            <span class="text-[9px] font-semibold text-[#a1978a]">2h</span>
                            <div class="w-full bg-[#e9e3da] rounded-t-md" style="height: 25%;"></div>
                            <span class="text-[9px] font-bold text-[#a79c8e]">MON</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 flex-1">
                            <span class="text-[9px] font-semibold text-[#a1978a]">6h</span>
                            <div class="w-full bg-[#e9e3da] rounded-t-md" style="height: 40%;"></div>
                            <span class="text-[9px] font-bold text-[#a79c8e]">TUE</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 flex-1">
                            <span class="text-[9px] font-semibold text-[#a1978a]">12h</span>
                            <div class="w-full bg-[#e9e3da] rounded-t-md" style="height: 55%;"></div>
                            <span class="text-[9px] font-bold text-[#a79c8e]">WED</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 flex-1">
                            <span class="text-[9px] font-semibold text-[#a1978a]">20h</span>
                            <div class="w-full bg-[#e9e3da] rounded-t-md" style="height: 70%;"></div>
                            <span class="text-[9px] font-bold text-[#a79c8e]">THU</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 flex-1">
                            <span class="text-[9px] font-semibold text-[#a1978a]">31h</span>
                            <div class="w-full bg-[#e9e3da] rounded-t-md" style="height: 82%;"></div>
                            <span class="text-[9px] font-bold text-[#a79c8e]">FRI</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 flex-1">
                            <span class="text-[9px] font-semibold text-[#a1978a]">40h</span>
                            <div class="w-full bg-[#e9e3da] rounded-t-md" style="height: 90%;"></div>
                            <span class="text-[9px] font-bold text-[#a79c8e]">SAT</span>
                        </div>
                        <div class="flex flex-col items-center gap-1.5 flex-1">
                            <span class="text-[9px] font-extrabold text-amber-800">48h</span>
                            <div class="w-full rounded-t-md shadow-sm" style="height: 100%; background: linear-gradient(180deg, #f2b705 0%, #e7b208 26%, #a8a422 52%, #6a8f33 76%, #3d7a3e 100%);"></div>
                            <span class="text-[9px] font-extrabold text-emerald-800">SUN</span>
                        </div>
                    </div>
                </div>

                <!-- CARD 5: SEARCH (.search) -->
                <div class="md:col-span-3 rounded-[22px] border-[1.6px] border-white/90 shadow-[0_4px_20px_rgba(24,30,45,0.045)] relative overflow-hidden p-6 flex flex-col justify-between min-h-[300px]"
                     style="background: linear-gradient(103deg, #eae9f5 0%, #e2e0f1 34%, #cfcdea 72%, #c2c0e6 100%);">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-indigo-800/70">Search &amp; Audit</span>
                        <h3 class="text-2xl font-extrabold text-[#0d0d10] mt-1 tracking-tight leading-tight">
                            Find anything<br/>instantly
                        </h3>
                    </div>

                    <!-- Search Input Box -->
                    <div class="mt-6 bg-white rounded-full p-2.5 flex items-center gap-3 shadow-md shadow-indigo-900/5 border border-white/90">
                        <div class="w-9 h-9 rounded-full bg-[#f1f1f5] flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-slate-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text"
                               placeholder="Search tasks, docs..."
                               class="w-full text-xs font-medium text-[#0d0d10] placeholder-[#8c8c99] bg-transparent border-none focus:outline-none focus:ring-0 p-0" />
                    </div>
                </div>
            </div>
        </main>

        <!-- Clean Footer -->
        <footer class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 border-t border-slate-200/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 font-medium">
            <div class="flex items-center gap-2">
                <x-application-logo class="h-6 w-6" />
                <span class="font-bold text-slate-800">Npontu Activity Tracker</span>
                <span>&bull; &copy; {{ date('Y') }}</span>
            </div>
            <div>
                Built for High-Performance Operational Teams
            </div>
        </footer>
    </body>
</html>

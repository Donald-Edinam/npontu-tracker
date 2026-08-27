<div role="status" id="toaster" x-data="toasterHub(@js($toasts), @js($config))" @class([
    'fixed z-50 p-4 max-w-md w-full flex flex-col pointer-events-none sm:p-6',
    'bottom-0' => $alignment->is('bottom'),
    'top-1/2 -translate-y-1/2' => $alignment->is('middle'),
    'top-0' => $alignment->is('top'),
    'items-start rtl:items-end left-0' => $position->is('left'),
    'items-center left-1/2 -translate-x-1/2' => $position->is('center'),
    'items-end rtl:items-start right-0' => $position->is('right'),
 ])>
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.isVisible"
             x-init="$nextTick(() => toast.show($el))"
             @if($alignment->is('bottom'))
             x-transition:enter-start="translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @elseif($alignment->is('top'))
             x-transition:enter-start="-translate-y-12 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             @else
             x-transition:enter-start="opacity-0 scale-90"
             x-transition:enter-end="opacity-100 scale-100"
             @endif
             x-transition:leave-end="opacity-0 scale-90"
             class="relative duration-300 transform transition ease-in-out max-w-sm w-full pointer-events-auto my-1.5"
        >
            <div class="rounded-[18px] border-[1.6px] border-white/90 p-4 shadow-[0_8px_30px_rgba(24,30,45,0.12)] flex items-center gap-3.5 backdrop-blur-md"
                 :class="toast.select({
                     error: 'bg-gradient-to-r from-white via-rose-50 to-rose-100 text-rose-950',
                     info: 'bg-gradient-to-r from-white via-indigo-50 to-indigo-100 text-indigo-950',
                     success: 'bg-gradient-to-r from-white via-emerald-50 to-emerald-100 text-emerald-950',
                     warning: 'bg-gradient-to-r from-white via-amber-50 to-amber-100 text-amber-950'
                 })"
            >
                <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0 text-white shadow-xs"
                     :class="toast.select({
                         error: 'bg-rose-600',
                         info: 'bg-indigo-600',
                         success: 'bg-emerald-600',
                         warning: 'bg-amber-600'
                     })"
                >
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </template>
                    <template x-if="toast.type === 'info'">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2L14.5 9.5L22 12L14.5 14.5L12 22L9.5 9.5L12 2Z"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error' || toast.type === 'warning'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </template>
                </div>

                <div class="flex-grow min-w-0 text-left">
                    <p x-text="toast.message" class="text-xs font-extrabold text-[#0d0d0d] leading-tight select-none"></p>
                </div>

                @includeWhen($closeable, 'toaster::close-button')
            </div>
        </div>
    </template>
</div>

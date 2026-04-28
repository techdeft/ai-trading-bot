<div
    class="md:hidden fixed bottom-0 left-0 right-0 bg-surface dark:bg-background-dark border-t border-slate-200 dark:border-slate-800 z-50 px-6 py-3 safe-area-pb">
    <div class="flex justify-between items-center">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-primary' : 'text-slate-500 hover:text-slate-300' }}">
            <span class="material-symbols-outlined text-2xl">dashboard</span>
            <span class="text-[10px] font-medium">Home</span>
        </a>

        <!-- Market -->
        <a href="{{ route('market') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('market') ? 'text-primary' : 'text-slate-500 hover:text-slate-300' }}">
            <span class="material-symbols-outlined text-2xl">monitoring</span>
            <span class="text-[10px] font-medium">Market</span>
        </a>

        <!-- Trade (Central Fab) -->
        <a href="{{ route('trade') }}" class="flex flex-col items-center justify-center -mt-8">
            <div
                class="w-14 h-14 rounded-full bg-primary text-white flex items-center justify-center shadow-lg shadow-primary/30 border-4 border-background-light dark:border-background-dark">
                <span class="material-symbols-outlined text-2xl">swap_horiz</span>
            </div>
            <span
                class="text-[10px] font-medium mt-1 {{ request()->routeIs('trade') ? 'text-primary' : 'text-slate-500' }}">Trade</span>
        </a>

        <!-- Portfolio -->
        <a href="{{ route('portfolio') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('portfolio') ? 'text-primary' : 'text-slate-500 hover:text-slate-300' }}">
            <span class="material-symbols-outlined text-2xl">account_balance_wallet</span>
            <span class="text-[10px] font-medium">Wallet</span>
        </a>

        <!-- Menu / Profile -->
        <a href="{{ route('profile.edit') }}"
            class="flex flex-col items-center gap-1 {{ request()->routeIs('profile.edit') ? 'text-primary' : 'text-slate-500 hover:text-slate-300' }}">
            <span class="material-symbols-outlined text-2xl">person</span>
            <span class="text-[10px] font-medium">Profile</span>
        </a>
    </div>
</div>
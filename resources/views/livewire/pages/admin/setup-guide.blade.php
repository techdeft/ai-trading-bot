<div class="p-6 lg:p-8">
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">Setup Documentation</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-2">Follow these steps to ensure your platform is correctly configured and operational.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Status Checklist -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-surface rounded-2xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-surface-accent/20">
                    <h3 class="font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">analytics</span>
                        System Health
                    </h3>
                </div>
                <div class="p-6 space-y-4">
                    <!-- Wallets Check -->
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $status['wallets'] ? 'bg-success/5 border border-success/10' : 'bg-danger/5 border border-danger/10' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ $status['wallets'] ? 'text-success' : 'text-danger' }}">
                                {{ $status['wallets'] ? 'check_circle' : 'warning' }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold {{ $status['wallets'] ? 'text-success-700' : 'text-danger-700' }}">Deposit Wallets</p>
                                <p class="text-xs text-slate-500 line-clamp-1">{{ $status['wallets'] ? 'Configured and ready' : 'No wallets found' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Bot Plans Check -->
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $status['bot_plans'] ? 'bg-success/5 border border-success/10' : 'bg-danger/5 border border-danger/10' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ $status['bot_plans'] ? 'text-success' : 'text-danger' }}">
                                {{ $status['bot_plans'] ? 'check_circle' : 'warning' }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold {{ $status['bot_plans'] ? 'text-success-700' : 'text-danger-700' }}">Investment Plans</p>
                                <p class="text-xs text-slate-500 line-clamp-1">{{ $status['bot_plans'] ? 'Active plans available' : 'No plans seeded' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Users Check -->
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $status['users'] ? 'bg-success/5 border border-success/10' : 'bg-primary/5 border border-primary/10' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ $status['users'] ? 'text-success' : 'text-primary' }}">
                                {{ $status['users'] ? 'person_add' : 'person_search' }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold {{ $status['users'] ? 'text-success-700' : 'text-primary-700' }}">User Base</p>
                                <p class="text-xs text-slate-500 line-clamp-1">{{ $status['users'] ? 'Users are registering' : 'Only admin account exists' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Scheduler Check -->
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $status['scheduler'] ? 'bg-success/5 border border-success/10' : 'bg-warning/5 border border-warning/10' }}">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined {{ $status['scheduler'] ? 'text-success' : 'text-warning' }}">
                                {{ $status['scheduler'] ? 'history_toggle_off' : 'event_busy' }}
                            </span>
                            <div>
                                <p class="text-sm font-semibold {{ $status['scheduler'] ? 'text-success-700' : 'text-warning-700' }}">Profit Scheduler</p>
                                <p class="text-xs text-slate-500 line-clamp-1">{{ $status['scheduler'] ? 'Running recently' : 'No recent activity detected' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Environment Details -->
            <div class="bg-white dark:bg-surface rounded-2xl border border-slate-200 dark:border-slate-800 p-6 space-y-4 shadow-sm">
                <h4 class="text-sm font-bold text-slate-400 uppercase tracking-wider">Environment info</h4>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Debug Mode</span>
                        <span class="font-mono {{ $status['debug_mode'] ? 'text-danger' : 'text-success' }}">{{ $status['debug_mode'] ? 'Enabled' : 'Disabled' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">App URL</span>
                        <span class="font-mono text-primary">{{ $status['app_url'] }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Database</span>
                        <span class="font-mono text-slate-900 dark:text-white">{{ $status['db_connection'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Setup Guide -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-white dark:bg-surface rounded-2xl border border-slate-200 dark:border-slate-800 p-8 shadow-sm">
                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Setup Instructions</h3>
                
                <div class="space-y-8">
                    <!-- Step 1 -->
                    <div class="flex gap-6">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">1</div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white mb-2">Environment Configuration</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-4">
                                Ensure your <code class="bg-slate-100 dark:bg-surface-accent px-1 rounded font-mono">.env</code> file is correctly set up with your database credentials. 
                                Make sure <code class="bg-slate-100 dark:bg-surface-accent px-1 rounded font-mono">APP_URL</code> is set to your actual domain.
                            </p>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex gap-6">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">2</div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white mb-2">Database Initialization</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-3">
                                Run the migrations and seeders to set up the default admin accounts and initial data:
                            </p>
                            <div class="bg-slate-900 rounded-lg p-4 font-mono text-xs text-slate-300">
                                php artisan migrate --seed
                            </div>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex gap-6">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">3</div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white mb-2">Scheduler Setup (Cron Job)</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed mb-4">
                                The AI Trading Bots require the scheduler to run every minute to generate profits. 
                                Add the following to your server's crontab:
                            </p>
                            <div class="bg-slate-900 rounded-lg p-4 font-mono text-xs text-slate-300">
                                * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
                            </div>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="flex gap-6">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">4</div>
                        <div>
                            <h4 class="font-bold text-slate-900 dark:text-white mb-2">Admin Configuration</h4>
                            <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">
                                Log in as admin (<code class="text-primary">admin@example.com</code>) and perform the following:
                            </p>
                            <ul class="mt-4 space-y-2 text-sm text-slate-500 dark:text-slate-400">
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-xs text-primary">circle</span>
                                    Add your BTC/USDT wallet addresses in <span class="text-primary font-medium">Admin > Wallets</span>.
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-xs text-primary">circle</span>
                                    Verify or create investment plans in <span class="text-primary font-medium">Admin > Bot Plans</span>.
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-xs text-primary">circle</span>
                                    Change your default password in <span class="text-primary font-medium">Profile Settings</span>.
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-primary/5 border border-primary/20 rounded-2xl p-6 flex gap-4 items-start">
                <span class="material-symbols-outlined text-primary text-3xl">info</span>
                <div>
                    <h5 class="font-bold text-slate-900 dark:text-white mb-1">Need help?</h5>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                        If you encounter any issues during setup, check the <code class="font-mono text-xs">storage/logs/laravel.log</code> file 
                        or contact the development team for assistance.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

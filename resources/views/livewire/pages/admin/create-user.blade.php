<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <a href="{{ route('admin.users') }}"
            class="inline-flex items-center gap-2 text-slate-400 hover:text-white mb-6 transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Users
        </a>

        <div class="bg-surface overflow-hidden shadow-xl sm:rounded-lg p-8">
            <h2 class="text-2xl font-bold text-white mb-6">Create New User</h2>

            <form wire:submit="createUser" class="space-y-6">
                <!-- Name -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Full Name</label>
                    <input wire:model="name" type="text"
                        class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    @error('name') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-400 mb-1">Email Address</label>
                    <input wire:model="email" type="email"
                        class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    @error('email') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Password</label>
                        <input wire:model="password" type="password"
                            class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        @error('password') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-400 mb-1">Confirm Password</label>
                        <input wire:model="password_confirmation" type="password"
                            class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2.5 focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>
                </div>

                <!-- Simulation Toggle -->
                <div class="pt-6 border-t border-slate-700">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="simulate_data" class="sr-only peer">
                            <div
                                class="w-11 h-6 bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/20 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary">
                            </div>
                        </div>
                        <span class="text-sm font-medium text-white">Simulate Investment Data</span>
                    </label>
                    <p class="text-xs text-slate-400 mt-2">If enabled, this will generate fake wallets, a past deposit,
                        and simulated daily trading profits to create a realistic account history.</p>
                </div>

                <!-- Simulation Options -->
                @if($simulate_data)
                    <div
                        class="grid md:grid-cols-2 gap-6 p-4 bg-background-dark/50 rounded-lg border border-slate-700/50 mt-4">
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">Initial Balance Simulation
                                ($)</label>
                            <input wire:model="simulation_balance" type="number" step="0.01"
                                class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            @error('simulation_balance') <span class="text-xs text-danger">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-slate-400 mb-1">History Duration (Days)</label>
                            <input wire:model="simulation_duration_days" type="number"
                                class="w-full bg-background-dark border border-slate-700 rounded-lg text-white p-2 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none">
                            @error('simulation_duration_days') <span class="text-xs text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                @endif

                <div class="pt-6">
                    <button type="submit"
                        class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3 px-4 rounded-lg transition-colors flex justify-center items-center gap-2">
                        <span class="material-symbols-outlined">person_add</span>
                        Create User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
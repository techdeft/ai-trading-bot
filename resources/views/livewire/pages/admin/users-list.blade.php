<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white">All Users</h2>
                <div class="flex gap-4">
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search users..."
                        class="bg-background-dark border border-border-dark text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5">
                    <a href="{{ route('admin.users.create') }}"
                        class="bg-primary hover:bg-primary/90 text-white font-bold py-2.5 px-4 rounded-lg transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined">add</span> Create
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-400">
                    <thead class="text-xs text-slate-500 uppercase bg-background-dark border-b border-border-dark">
                        <tr>
                            <th scope="col" class="px-6 py-3">Name</th>
                            <th scope="col" class="px-6 py-3">Email</th>
                            <th scope="col" class="px-6 py-3">Balance</th>
                            <th scope="col" class="px-6 py-3">KYC Status</th>
                            <th scope="col" class="px-6 py-3">Joined</th>
                            <th scope="col" class="px-6 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="border-b border-border-dark hover:bg-surface-accent/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-white">
                                    {{ $user->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->email }}
                                </td>
                                <td class="px-6 py-4">
                                    ${{ number_format($user->balance, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if($user->kyc_status === 'approved') bg-green-100 text-green-800
                                                        @elseif($user->kyc_status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($user->kyc_status === 'rejected') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($user->kyc_status ?? 'Unverified') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ $user->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.users.show', $user) }}"
                                        class="text-primary hover:text-primary/80 font-bold transition-all flex items-center justify-end gap-1">
                                        Details
                                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-slate-500">No users found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
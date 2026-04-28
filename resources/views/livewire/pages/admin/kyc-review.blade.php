<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-surface overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-white">KYC Verifications</h2>
                <select wire:model.live="status"
                    class="bg-background-dark border border-border-dark text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-400">
                    <thead class="text-xs text-slate-500 uppercase bg-background-dark border-b border-border-dark">
                        <tr>
                            <th scope="col" class="px-6 py-3">User</th>
                            <th scope="col" class="px-6 py-3">Document Type</th>
                            <th scope="col" class="px-6 py-3">Submitted At</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kycs as $kyc)
                            <tr class="border-b border-border-dark hover:bg-surface-accent/50 transition-colors">
                                <td class="px-6 py-4 font-medium text-white">
                                    {{ $kyc->first_name }} {{ $kyc->last_name }}
                                    <div class="text-xs text-slate-500">{{ $kyc->user->email }}</div>
                                </td>
                                <td class="px-6 py-4 uppercase">
                                    {{ str_replace('_', ' ', $kyc->document_type) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $kyc->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.kyc.details', $kyc->id) }}"
                                        class="text-primary hover:text-primary/80 font-bold">Review</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-slate-500">No verifications found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $kycs->links() }}
            </div>
        </div>
    </div>
</div>
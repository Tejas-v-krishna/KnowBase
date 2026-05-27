<x-admin-layout>
    <x-slot name="title">Reports Queue</x-slot>

    <div class="space-y-6">
        <h2 class="text-xl font-bold font-outfit text-black ">Moderation Queue</h2>
        
        <div class="bg-white border border-black rounded-3xl overflow-hidden shadow-sm">
            <table class="min-w-full divide-y divide-slate-100 ">
                <thead class="bg-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-black uppercase tracking-wider">Reporter</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-black uppercase tracking-wider">Content Type</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-black uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-black uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-black uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 ">
                    @forelse($reports as $report)
                        <tr class="hover:bg-white/50 :bg-white/20">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black ">
                                {{ $report->user->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-black ">
                                {{ class_basename($report->reportable_type) }} #{{ $report->reportable_id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-black ">
                                {{ $report->reason }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 text-[10px] font-bold uppercase rounded-lg {{ $report->status === 'pending' ? 'bg-gray-100 text-black font-bold font-bold' : ($report->status === 'resolved' ? 'bg-gray-100 text-black font-bold font-bold' : 'bg-white text-black bg-black ') }}">
                                    {{ $report->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex space-x-2">
                                @if($report->status === 'pending')
                                    <form action="{{ route('admin.reports.dismiss', $report) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-black hover:text-black :text-black">Dismiss</button>
                                    </form>
                                    <form action="{{ route('admin.reports.resolve', $report) }}" method="POST" onsubmit="return confirm('Delete this content completely?');">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900 :text-red-300">Delete Content</button>
                                    </form>
                                @else
                                    <span class="text-black text-xs italic">Closed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-black text-sm">No reports found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4 border-t border-black ">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>





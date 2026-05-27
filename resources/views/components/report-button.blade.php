@props(['type', 'id'])

<div x-data="{ showReportModal: false }">
    <button @click="showReportModal = true" class="p-2 border border-slate-200 text-slate-500 rounded-xl hover:bg-slate-50 hover:text-slate-900 transition-colors" title="Report Content">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9" />
        </svg>
    </button>

    <div x-show="showReportModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showReportModal" x-transition.opacity class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showReportModal" @click.away="showReportModal = false" x-transition.scale.origin.bottom class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-slate-200">
                <form action="{{ route('reports.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reportable_type" value="{{ $type }}">
                    <input type="hidden" name="reportable_id" value="{{ $id }}">
                    
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-rose-50 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-bold text-slate-900 font-outfit" id="modal-title">
                                    Report Content
                                </h3>
                                <div class="mt-2 space-y-4">
                                    <p class="text-sm text-slate-600">
                                        Please provide a reason for reporting this content. Our moderators will review it shortly.
                                    </p>
                                    <div>
                                        <select name="reason" class="block w-full border-slate-300 rounded-lg shadow-sm focus:ring-slate-900 focus:border-slate-900 sm:text-sm">
                                            <option value="Spam">Spam</option>
                                            <option value="Harassment or Abuse">Harassment or Abuse</option>
                                            <option value="Inappropriate Content">Inappropriate Content</option>
                                            <option value="Plagiarism">Plagiarism</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                        <button type="submit" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-slate-900 text-base font-bold text-white hover:bg-slate-800 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Submit Report
                        </button>
                        <button type="button" @click="showReportModal = false" class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-4 py-2 bg-white text-base font-bold text-slate-700 hover:bg-slate-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>





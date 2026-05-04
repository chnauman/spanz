<div id="share-docs-modal" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/50 p-4" role="dialog" aria-modal="true" aria-labelledby="share-docs-title">
    <div class="max-h-[90vh] w-full max-w-lg overflow-y-auto rounded-xl border border-gray-200 bg-white p-6 shadow-xl">
        <div class="flex items-start justify-between gap-4">
            <h2 id="share-docs-title" class="text-lg font-bold text-[#032747]">Share documents</h2>
            <button type="button" id="close-share-docs-modal" class="rounded p-1 text-gray-500 hover:bg-gray-100 hover:text-gray-800" aria-label="Close">&times;</button>
        </div>
        <p class="mt-2 text-sm text-gray-600">Upload one or more files. Each selected supplier (up to 3) can download them from <strong>Received documents</strong> in their dashboard.</p>

        <form method="POST" action="{{ route('suppliers.documents.share') }}" enctype="multipart/form-data" class="mt-5 space-y-4" id="share-docs-form" novalidate>
            @csrf
            <div id="share-recipient-inputs"></div>

            <div>
                <span class="mb-2 block text-sm font-semibold text-gray-800">Files <span class="text-red-600">*</span></span>
                <input type="file" id="share-docs-files" name="files[]" multiple
                    class="sr-only"
                    accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg,.webp,.gif,.zip,.txt,.csv,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/zip">

                <button type="button" id="share-docs-dropzone" aria-controls="share-docs-files" aria-label="Choose files or drop files here"
                    class="group flex w-full cursor-pointer flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-gray-300 bg-gradient-to-b from-slate-50 to-white px-5 py-10 text-center transition-colors hover:border-[#0d6aed] hover:bg-blue-50/50 focus:outline-none focus:ring-2 focus:ring-[#0d6aed] focus:ring-offset-2">
                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100 text-[#0d6aed] ring-4 ring-white group-hover:bg-blue-200" aria-hidden="true">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l-3 3m3-3l3 3M6.75 19.5h10.5a2.25 2.25 0 002.25-2.25V6.75a2.25 2.25 0 00-2.25-2.25H6.75A2.25 2.25 0 004.5 6.75v10.5a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </span>
                    <span>
                        <span class="text-base font-semibold text-[#032747]">Drop files here</span>
                        <span class="text-[#032747]"> or </span>
                        <span class="text-base font-semibold text-[#0d6aed] underline decoration-2 underline-offset-2 group-hover:text-blue-700">browse</span>
                    </span>
                    <span class="max-w-sm text-xs leading-relaxed text-gray-500">You can select <strong>multiple files</strong> in the file picker (Ctrl+click or Shift+click on Windows, Cmd+click on Mac), or add more by dropping again.</span>
                </button>

                <ul id="share-docs-file-list" class="mt-3 hidden max-h-48 space-y-2 overflow-y-auto rounded-lg border border-gray-200 bg-gray-50/80 p-3"></ul>

                <div class="mt-2 flex flex-wrap items-center justify-between gap-2">
                    <p id="share-files-hint" class="text-xs text-gray-500">Up to <strong>10</strong> files, <strong>15 MB</strong> each. PDF, Office, images, ZIP, CSV, TXT.</p>
                    <button type="button" id="share-docs-clear-files" class="hidden text-xs font-semibold text-red-600 hover:text-red-800 hover:underline">Clear all</button>
                </div>
            </div>

            <div>
                <label for="share-docs-message" class="mb-1 block text-sm font-semibold text-gray-800">Message <span class="font-normal text-gray-500">(optional)</span></label>
                <textarea id="share-docs-message" name="message" rows="3" maxlength="2000"
                    class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-800 focus:border-[#0d6aed] focus:outline-none focus:ring-1 focus:ring-[#0d6aed]"
                    placeholder="Short note for recipients…">{{ old('message') }}</textarea>
            </div>

            <div class="flex flex-wrap items-center justify-end gap-2 pt-2">
                <button type="button" id="cancel-share-docs-modal" class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-800 hover:bg-gray-50">Cancel</button>
                <button type="submit" class="rounded-md bg-[#0d6aed] px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Send</button>
            </div>
        </form>
    </div>
</div>

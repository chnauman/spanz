<?php

namespace App\Http\Controllers;

use App\Models\SupplierDocumentShare;
use App\Models\SupplierDocumentShareFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SupplierReceivedDocumentsController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if (! $user->isSupplier() && ! $user->isSubSupplier()) {
            abort(403);
        }

        $receipts = $user->supplierDocumentShareRecipients()
            ->with([
                'share.sender.companyDetail',
                'share.files',
            ])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('suppliers.received-documents', compact('receipts'));
    }

    public function download(Request $request, SupplierDocumentShare $share, SupplierDocumentShareFile $file)
    {
        $user = $request->user();
        if (! $user->isSupplier() && ! $user->isSubSupplier()) {
            abort(403);
        }

        abort_unless((int) $file->supplier_document_share_id === (int) $share->id, 404);

        $isRecipient = $share->recipients()
            ->where('recipient_user_id', $user->id)
            ->exists();

        abort_unless($isRecipient, 403);

        if (! Storage::disk('public')->exists($file->path)) {
            abort(404, 'File no longer available.');
        }

        return Storage::disk('public')->download($file->path, $file->original_name);
    }
}

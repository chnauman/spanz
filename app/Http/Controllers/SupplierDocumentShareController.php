<?php

namespace App\Http\Controllers;

use App\Models\SupplierDocumentShare;
use App\Models\SupplierDocumentShareFile;
use App\Models\SupplierDocumentShareRecipient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierDocumentShareController extends Controller
{
    public function store(Request $request)
    {
        $user = $request->user();
        if (! $user->isBuyer() && ! $user->isSupplier() && ! $user->isSubSupplier()) {
            abort(403, 'Only buyers and suppliers can share documents from the directory.');
        }

        $validated = $request->validate([
            'recipient_ids' => ['required', 'array', 'min:1', 'max:3'],
            'recipient_ids.*' => ['required', 'integer', 'distinct', 'exists:users,id'],
            'files' => ['required', 'array', 'min:1', 'max:10'],
            'files.*' => ['file', 'max:15360'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $recipientIds = collect($validated['recipient_ids'])->unique()->values();

        if ($recipientIds->contains($user->id)) {
            return back()->withErrors(['recipient_ids' => 'You cannot include yourself as a recipient.'])->withInput();
        }

        $recipients = User::query()
            ->whereIn('id', $recipientIds->all())
            ->whereIn('role', ['supplier', 'sub_supplier'])
            ->where('is_approved', true)
            ->whereHas('companyDetail')
            ->get();

        if ($recipients->count() !== $recipientIds->count()) {
            return back()->withErrors(['recipient_ids' => 'Each recipient must be an approved supplier with a company profile.'])->withInput();
        }

        $share = DB::transaction(function () use ($user, $validated, $recipientIds, $request) {
            $share = SupplierDocumentShare::create([
                'sender_id' => $user->id,
                'message' => $validated['message'] ?? null,
            ]);

            foreach ($recipientIds as $rid) {
                SupplierDocumentShareRecipient::create([
                    'supplier_document_share_id' => $share->id,
                    'recipient_user_id' => $rid,
                ]);
            }

            foreach ($request->file('files', []) as $file) {
                $stored = $file->store('supplier-document-shares/' . $share->id, 'public');
                SupplierDocumentShareFile::create([
                    'supplier_document_share_id' => $share->id,
                    'path' => $stored,
                    'original_name' => $file->getClientOriginalName(),
                    'mime' => $file->getClientMimeType(),
                    'size' => $file->getSize(),
                ]);
            }

            return $share->load(['sender.companyDetail', 'files', 'recipients.recipient']);
        });

        return redirect()
            ->route('suppliers.directory')
            ->with('success', 'Documents sent successfully. Each selected supplier can open them from their dashboard under “Received documents”.');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierDocumentShareRecipient extends Model
{
    protected $fillable = [
        'supplier_document_share_id',
        'recipient_user_id',
    ];

    public function share(): BelongsTo
    {
        return $this->belongsTo(SupplierDocumentShare::class, 'supplier_document_share_id');
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }
}

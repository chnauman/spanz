<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierDocumentShareFile extends Model
{
    protected $fillable = [
        'supplier_document_share_id',
        'path',
        'original_name',
        'mime',
        'size',
    ];

    public function share(): BelongsTo
    {
        return $this->belongsTo(SupplierDocumentShare::class, 'supplier_document_share_id');
    }
}

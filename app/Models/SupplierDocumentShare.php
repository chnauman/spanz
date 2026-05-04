<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierDocumentShare extends Model
{
    protected $fillable = [
        'sender_id',
        'message',
    ];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipients(): HasMany
    {
        return $this->hasMany(SupplierDocumentShareRecipient::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(SupplierDocumentShareFile::class);
    }
}

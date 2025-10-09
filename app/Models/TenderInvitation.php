<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TenderInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'user_id',
        'status',
        'viewed_at',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
            'responded_at' => 'datetime',
        ];
    }

    public function tender()
    {
        return $this->belongsTo(Tender::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function markAsViewed()
    {
        $this->update([
            'status' => 'viewed',
            'viewed_at' => now(),
        ]);
    }

    public function markAsResponded()
    {
        $this->update([
            'status' => 'responded',
            'responded_at' => now(),
        ]);
    }
}

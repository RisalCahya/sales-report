<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    protected $fillable = [
        'user_id',
        'tanggal',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    /**
     * Get the user that owns the report.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withDefault([
            'name' => 'Akun Sales Tidak Aktif',
            'email' => '-',
        ]);
    }

    /**
     * Get the details for the report.
     */
    public function details(): HasMany
    {
        return $this->hasMany(ReportDetail::class);
    }
}

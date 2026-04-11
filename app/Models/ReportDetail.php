<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportDetail extends Model
{
    protected $fillable = [
        'report_id',
        'outlet',
        'alamat',
        'pic',
        'keterangan',
        'foto_path',
        'captured_at_label',
        'latitude',
        'longitude',
    ];

    /**
     * Get the report that owns the detail.
     */
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }
}

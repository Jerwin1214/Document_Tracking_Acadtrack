<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Enrollment;
use App\Models\Document;

class StudentDocument extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = [
        'enrollment_id',
        'document_id',
        'status',
        'file_path',
        'remarks',
        'submitted_at',
    ];

    // ✅ Enable timestamps
    public $timestamps = true;

    /**
     * Relationship: StudentDocument belongs to an Enrollment
     */
    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    /**
     * Relationship: StudentDocument belongs to a Document
     */
    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}

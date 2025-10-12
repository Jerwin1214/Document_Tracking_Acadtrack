<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\StudentDocument;

class Document extends Model
{
    protected $fillable = ['name'];

    /**
     * Relationship: Document has many StudentDocuments
     */
    public function studentDocuments()
    {
        return $this->hasMany(StudentDocument::class, 'document_id', 'id');
    }
}

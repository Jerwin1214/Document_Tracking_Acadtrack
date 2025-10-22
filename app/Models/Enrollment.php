<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\StudentDocument;

class Enrollment extends Model
{
    use HasFactory;

    protected $table = 'enrollments';

    protected $fillable = [
        'school_year',
        'grade_level', // ✅ added this column for year level
        'with_lrn',
        'returning',
        'last_name',
        'first_name',
        'middle_name',
        'extension_name',
        'psa_birth_cert_no',
        'lrn',
        'learner_reference_no',
        'birthdate',
        'place_of_birth',
        'sex',
        'mother_tongue',

        // ✅ Updated additional learner details as text
        'indigenous_people',      // previously is_ip / ip_specify
        'fourps_beneficiary',     // previously is_4ps_beneficiary / household_id_no
        'learner_with_disability', // previously is_pwd / disability_type

        'household_id_no',        // optional, can keep if needed
        'disability_type',        // optional, can keep if needed

        'current_house_no',
        'current_street',
        'current_barangay',
        'current_city',
        'current_province',
        'current_country',
        'current_zip',
        'same_address',
        'permanent_house_no',
        'permanent_street',
        'permanent_barangay',
        'permanent_city',
        'permanent_province',
        'permanent_country',
        'permanent_zip',
        'father_last_name',
        'father_first_name',
        'father_middle_name',
        'father_contact',
        'mother_last_name',
        'mother_first_name',
        'mother_middle_name',
        'mother_contact',
        'guardian_last_name',
        'guardian_first_name',
        'guardian_middle_name',
        'guardian_contact',
        'status', // active / archived
    ];

    protected $casts = [
        'birthdate' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    // ✅ Computed age attribute
    public function getAgeAttribute()
    {
        return $this->birthdate ? Carbon::parse($this->birthdate)->age : null;
    }

    // ✅ Scope for active enrollments
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // ✅ Scope for archived enrollments
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    // ✅ Relationship to submitted documents
    public function studentDocuments()
    {
        return $this->hasMany(StudentDocument::class, 'enrollment_id')->with('document');
    }

    // Alias for documents
    public function documents()
    {
        return $this->studentDocuments();
    }
}

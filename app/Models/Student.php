<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Guardian;
use App\Models\Classes;
use App\Models\SubjectStream;
use App\Models\User;
use App\Models\Subject;
use App\Models\Teacher;

// /**
//  * App\Models\Student
//  *
//  * @property int $id
//  * @property string $lrn
//  * @property string $first_name
//  * @property string|null $middle_name
//  * @property string $last_name
//  * @property string $gender
//  * @property string $dob
//  * @property string $age
//  * @property string $address
//  * @property string $status
//  * @property string $guardian_id
//  * @property string|null $department
//  * @property string|null $year_level
//  * @property string|null $section
//  * @property string|null $strand
//  */
/**
 * App\Models\Student
 *
 * @property int $id
 * @property string $student_id
 * @property string $lrn
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $extension_name
 * @property string|null $birthdate
 * @property string|null $place_of_birth
 * @property string|null $sex
 * @property string|null $mother_tongue
 * @property string|null $current_house_no
 * @property string|null $current_street
 * @property string|null $current_barangay
 * @property string|null $current_city
 * @property string|null $current_province
 * @property string|null $current_country
 * @property string|null $current_zip
 * @property string|null $permanent_house_no
 * @property string|null $permanent_street
 * @property string|null $permanent_barangay
 * @property string|null $permanent_city
 * @property string|null $permanent_province
 * @property string|null $permanent_country
 * @property string|null $permanent_zip
 * @property string|null $father_first_name
 * @property string|null $father_middle_name
 * @property string|null $father_last_name
 * @property string|null $father_contact
 * @property string|null $mother_first_name
 * @property string|null $mother_middle_name
 * @property string|null $mother_last_name
 * @property string|null $mother_contact
 * @property string|null $guardian_first_name
 * @property string|null $guardian_middle_name
 * @property string|null $guardian_last_name
 * @property string|null $guardian_contact
 * @property string $gender
 * @property string $dob
 * @property string|null $department
 * @property string|null $year_level
 * @property string|null $section
 * @property string|null $strand
 * @property string $status
 * @property string $user_id
 */

class Student extends Model
{
    use HasFactory;

    // === Explicitly declare properties to fix PHP6602 warnings ===
    // public ?string $year_level = null;
    // public ?string $strand = null;
    // public ?string $department = null;

    protected $fillable = [
        'student_id',
        'lrn',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'dob',
        'age',
        'address',
        'user_id',
        'guardian_id',
        'status',
        'department',
        'year_level',
        'section',
        'strand',
    ];

    protected $casts = [
        'dob' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ================================
    // 🔗 Relationships
    // ================================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'class_student', 'student_id', 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'grades')
                    ->withPivot('quarter', 'grade', 'remarks', 'teacher_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function subjectStream()
    {
        return $this->belongsTo(SubjectStream::class);
    }

    // 🔗 NEW: Many-to-Many relationship with teachers
    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'student_teacher', 'student_id', 'teacher_id')
                    ->withTimestamps();
    }

    // ================================
    // 🔗 NEW: Grade table relationships
    // ================================
    public function kinderGrades()
    {
        return $this->hasMany(KindergartenGrade::class, 'student_id');
    }

    public function elementaryGrades()
    {
        return $this->hasMany(ElementaryGrade::class, 'student_id');
    }

    public function juniorHighGrades()
    {
        return $this->hasMany(JuniorHighGrade::class, 'student_id');
    }

    public function seniorHighGrades()
    {
        return $this->hasMany(SeniorHighGrade::class, 'student_id');
    }

    // ================================
    // 🔗 Accessors
    // ================================
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }

public function enrollment()
{
    return $this->hasOne(Enrollment::class);
}


}

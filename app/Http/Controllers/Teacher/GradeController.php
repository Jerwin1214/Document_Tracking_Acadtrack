<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Classes;
use App\Models\Grade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\KinderGrade;
use App\Models\ElementaryGrade;
use App\Models\JuniorHighGrade;
use App\Models\KindergartenGrade;
use App\Models\SeniorHighGrade;


class GradeController extends Controller
{
    public function index()
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $classes = $teacher->classes()->get();

        return view('pages.teachers.grades.classes', compact('classes'));
    }

    public function classSubjects($classId)
    {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $class = Classes::findOrFail($classId);

        $subjects = Subject::whereHas('teachers', function ($q) use ($teacher) {
            $q->where('teacher_id', $teacher->id);
        })->get();

        return view('pages.teachers.grades.class-subjects', compact('class', 'subjects'));
    }

 public function classStudents($classId)
{
    // Make sure the logged-in teacher can access this class
    $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();

    // Get the class
    $class = Classes::findOrFail($classId);

    // Get students assigned to this class
    $students = $class->students()->orderBy('last_name')->get();

    // Pick a subject for grading automatically
    // For simplicity, pick the first subject in the mapping
    $department = strtolower($class->department ?? 'kindergarten');

    $subjectMap = [
        'kindergarten' => [
            'language_literacy' => 'Language Arts and Literacy',
            'mathematics' => 'Mathematics',
            'science_social' => 'Science and Social Studies',
            'social_emotional' => 'Social-Emotional Learning',
            'creative_arts_pe' => 'Creative Arts and Physical Education',
        ],
        'elementary' => ['filipino','english','reading_and_literacy','mathematics','science','makabansa','ap','mapeh','epp','tle','esp','gmrc'],
        'junior_high' => ['filipino','english','mathematics','science','ap','mapeh','tle','values_education'],
        'senior_high' => [
            'oral_communication','reading_and_writing_skills','komunikasyon_at_pananaliksik','pagbasa_at_pagsusuri',
            'general_mathematics','earth_and_life_science','personal_development','understanding_culture_society',
            'physical_education_and_health','empowerment_technologies','practical_research_1','lit_21st_century',
            'contemporary_phil_arts','media_and_info_lit','statistics_and_probability','physical_science',
            'philosophy','practical_research_2','filipino_sa_piling_larangan','entrepreneurship','inquiries_investigations_immersion'
        ],
    ];

    // Default subject for the grading form
    if($department === 'kindergarten'){
        $subjectName = 'Language Arts and Literacy';
    } else {
        $subjectName = $subjectMap[$department][0] ?? null;
    }

    $subject = Subject::where('name', 'like', "%$subjectName%")->first();

    return view('pages.teachers.grades.class-students', compact('class', 'students', 'subject'));
}


    public function subjectStudents($classId, $subjectId)
    {
        $class = Classes::findOrFail($classId);
        $subject = Subject::findOrFail($subjectId);

        $students = $class->students()->orderBy('last_name')->get();

        return view('pages.teachers.grades.subject-students', compact('class', 'subject', 'students'));
    }

public function create($studentId)
{
    $student = Student::findOrFail($studentId);

    // Get student's class
    $class = $student->classes()->first();
    $department = strtolower($class->department ?? 'kindergarten');

    // Current school year
    // $schoolYear = date('Y') . '-' . (date('Y') + 1);
    $quarter = '1'; // default quarter for kindergarten, or 1st quarter

    // Determine grade table and columns based on department
    switch ($department) {
        case 'kindergarten':
            $table = 'kinder_grades';
            $columns = [
                'language_literacy','mathematics','science_social','social_emotional','creative_arts_pe'
            ];
            break;
        case 'elementary':
            $table = 'elementary_grades';
            $columns = ['filipino','english','reading_and_literacy','mathematics','science','makabansa','ap','mapeh','epp','tle','esp','gmrc'];
            break;
        case 'junior_high':
            $table = 'junior_high_grades';
            $columns = ['filipino','english','mathematics','science','ap','mapeh','tle','values_education'];
            break;
        case 'senior_high':
            $table = 'senior_high_grades';
            $columns = [
                'oral_communication','reading_and_writing_skills','komunikasyon_at_pananaliksik','pagbasa_at_pagsusuri',
                'general_mathematics','earth_and_life_science','personal_development','understanding_culture_society',
                'physical_education_and_health','empowerment_technologies','practical_research_1','lit_21st_century',
                'contemporary_phil_arts','media_and_info_lit','statistics_and_probability','physical_science',
                'philosophy','practical_research_2','filipino_sa_piling_larangan','entrepreneurship','inquiries_investigations_immersion'
            ];
            break;
        default:
            return redirect()->back()->with('error','Invalid department.');
    }

    // Load existing grade if any
    if ($department === 'kindergarten') {
        // Kindergarten stores each subject per quarter in columns like language_literacy_q1, language_literacy_q2, etc.
        $existingGrade = DB::table($table)
            ->where('student_id', $student->id)
            // ->where('school_year', $schoolYear)
            ->first(); // returns stdClass with all columns
        $quarter = null; // not needed for the Blade form, selects already handle Q1–Q4
    } else {
        // Elementary, JH, SH use grading_quarter
        $gradingQuarter = '1st'; // default
        $existingGrade = DB::table($table)
            ->where('student_id', $student->id)
            // ->where('school_year', $schoolYear)
            ->where('grading_quarter', $gradingQuarter)
            ->first();
    }

    // Map columns to display names for the form
    $gradingFields = [];
    foreach ($columns as $col) {
        $gradingFields[$col] = ucwords(str_replace('_',' ', $col));
    }

    return view('pages.teachers.grades.create', compact(
        'student', 'class', 'department', 'existingGrade', 'gradingFields', 'quarter'
    ));
}

public function store(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'class_id'   => 'required|exists:classes,id',
        'department' => 'required|string',
        // 'school_year'=> 'required|string',
        'grades'     => 'required|array',
        'grading_quarter' => 'sometimes|string', // optional for Kindergarten
    ]);

    try {
        $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
        $student = Student::findOrFail($request->student_id);
        $class   = Classes::findOrFail($request->class_id);
        $department = strtolower($request->department);
        // $schoolYear = $request->school_year;
        $grades  = $request->grades;
        $gradingQuarter = $request->grading_quarter ?? '1st';

        $gradeLevel = $student->year_level ?? $class->year_level ?? 0;
        $strand     = $student->strand ?? $class->strand ?? null;

        // Columns mapping
        switch($department) {
            case 'kindergarten':
                $gradingFields = ['language_literacy','mathematics','science_social','social_emotional','creative_arts_pe'];
                break;
            case 'elementary':
                $gradingFields = ['filipino','english','reading_and_literacy','mathematics','science','makabansa','ap','mapeh','epp','tle','esp','gmrc'];
                break;
            case 'junior_high':
                $gradingFields = ['filipino','english','mathematics','science','ap','mapeh','tle','values_education'];
                break;
            case 'senior_high':
                $gradingFields = ['oral_communication','reading_and_writing_skills','komunikasyon_at_pananaliksik','pagbasa_at_pagsusuri','general_mathematics','earth_and_life_science','personal_development','understanding_culture_society','physical_education_and_health','empowerment_technologies','practical_research_1','lit_21st_century','contemporary_phil_arts','media_and_info_lit','statistics_and_probability','physical_science','philosophy','practical_research_2','filipino_sa_piling_larangan','entrepreneurship','inquiries_investigations_immersion'];
                break;
            default:
                return redirect()->back()->with('error','Invalid department.');
        }

        $tableMap = [
            'kindergarten' => 'kinder_grades',
            'elementary' => 'elementary_grades',
            'junior_high' => 'junior_high_grades',
            'senior_high' => 'senior_high_grades',
        ];
        $table = $tableMap[$department];

        $columnsInTable = Schema::getColumnListing($table);

        if($department === 'kindergarten'){
            foreach($grades as $subject => $quarterGrades){
                foreach($quarterGrades as $q => $value){
                    if($value === null || $value === '') continue;
                    $data = [
                        'student_id' => $student->id,
                        'teacher_id' => $teacher->id,
                        'class_id'   => $class->id,
                        'quarter'    => $q,
                        // 'school_year'=> $schoolYear,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    if(in_array($subject,$columnsInTable)) $data[$subject] = $value;
                    DB::table($table)->updateOrInsert(
                        [
                            'student_id' => $student->id,
                            'class_id' => $class->id,
                            'quarter' => $q,
                            // 'school_year' => $schoolYear,
                        ],
                        $data
                    );
                }
            }
        } else {
            $gradesData = [];
            foreach($gradingFields as $subject){
                if(isset($grades[$subject]) && $grades[$subject] !== null && $grades[$subject] !== ''){
                    $gradesData[$subject] = $grades[$subject];
                }
            }

            if(in_array('student_id',$columnsInTable)) $gradesData['student_id'] = $student->id;
            if(in_array('teacher_id',$columnsInTable)) $gradesData['teacher_id'] = $teacher->id;
            if(in_array('class_id',$columnsInTable)) $gradesData['class_id'] = $class->id;
            if(in_array('grading_quarter',$columnsInTable)) $gradesData['grading_quarter'] = $gradingQuarter;
            // if(in_array('school_year',$columnsInTable)) $gradesData['school_year'] = $schoolYear;
            if(in_array('grade_level',$columnsInTable)) $gradesData['grade_level'] = $gradeLevel;
            if(in_array('strand',$columnsInTable)) $gradesData['strand'] = $strand;
            $gradesData['created_at'] = now();
            $gradesData['updated_at'] = now();

            DB::table($table)->updateOrInsert(
                [
                    'student_id' => $student->id,
                    'class_id' => $class->id,
                    'grading_quarter' => $gradingQuarter,
                    // 'school_year' => $schoolYear,
                ],
                $gradesData
            );
        }

        return redirect()->back()->with('success','Grades saved successfully!');

    } catch(\Exception $e){
        return redirect()->back()->with('error','Failed to save grades: '.$e->getMessage());
    }
}

private function getExistingGrade($studentId, $subjectColumn, $classId, $quarter)
{
    return DB::table('kinder_grades')
        ->where('student_id', $studentId)
        ->where('class_id', $classId)
        ->where('quarter', $quarter)
        ->select($subjectColumn)
        ->first();
}



public function selectClassView()
{
    $teacher = auth()->user()->teacher;
    $classes = $teacher->classes;

    return view('pages.teachers.grades.select-class', compact('classes'));
}
public function showClassSubjects($classId)
{
    $teacher = Teacher::where('user_id', Auth::id())->firstOrFail();
    $class = Classes::with('students')->findOrFail($classId);

    // Fetch subjects assigned to this teacher
    $subjects = Subject::whereHas('teachers', function ($q) use ($teacher) {
        $q->where('teacher_id', $teacher->id);
    })->get();

    // Return the renamed view
    return view('pages.teachers.grades.subjects-view', compact('class', 'subjects'));
}


public function showClassSubjectGrades($classId, $subjectId = null)
{
    $teacher = auth()->user()->teacher;

    // Fetch the class with students
    $class = Classes::with('students')->findOrFail($classId);

    // Fetch the subject if provided
    $subject = $subjectId ? Subject::findOrFail($subjectId) : null;

    // Normalize the level
    $level = strtolower(trim($class->year_level ?? $class->level ?? ''));

    $gradeRelation = '';
    $gradeModel = null;

    // Map year levels / numeric grades to grade table
    $elementaryGrades = ['1','2','3','4','5','6','grade 1','grade 2','grade 3','grade 4','grade 5','grade 6','elementary'];
    $juniorHighGrades = ['7','8','9','10','grade 7','grade 8','grade 9','grade 10','junior high'];
    $seniorHighGrades = ['11','12','grade 11','grade 12','senior high'];

    if (str_contains($level, 'kindergarten')) {
        $gradeRelation = 'kinderGrades';
        $gradeModel = KindergartenGrade::class;

    } elseif (in_array($level, $elementaryGrades)) {
        $gradeRelation = 'elementaryGrades';
        $gradeModel = ElementaryGrade::class;

    } elseif (in_array($level, $juniorHighGrades)) {
        $gradeRelation = 'juniorHighGrades';
        $gradeModel = JuniorHighGrade::class;

    } elseif (in_array($level, $seniorHighGrades)) {
        $gradeRelation = 'seniorHighGrades';
        $gradeModel = SeniorHighGrade::class;

    } else {
        abort(404, "No grade table found for this class level: $level");
    }

    // Fetch students with grades filtered by class and subject
    $students = $class->students()
        ->with([$gradeRelation => function ($query) use ($class, $subject, $gradeModel) {
            if (Schema::hasColumn((new $gradeModel)->getTable(), 'class_id')) {
                $query->where('class_id', $class->id);
            }
            if ($subject && Schema::hasColumn((new $gradeModel)->getTable(), 'subject_id')) {
                $query->where('subject_id', $subject->id);
            }
        }])
        ->orderBy('last_name')
        ->orderBy('first_name')
        ->orderBy('middle_name')
        ->get();

    return view('pages.teachers.grades.view-class-subject-grades', compact(
        'class',
        'subject',
        'students',
        'gradeRelation'
    ));
}




}

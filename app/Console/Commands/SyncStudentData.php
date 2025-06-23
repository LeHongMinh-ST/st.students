<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\Gender;
use App\Enums\Status;
use App\Models\AdmissionYear;
use App\Models\ClassGenerate;
use App\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class SyncStudentData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-student-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        DB::beginTransaction();
        try {
            $oldAdmissionYear = DB::connection('old_db')->table('admission_years')->get();
            foreach ($oldAdmissionYear as $oldAdmission) {
                $oldClass = DB::connection('old_db')->table('classes')->where('admission_year_id', $oldAdmission->id)->get();
                $admissionYear = AdmissionYear::where('admission_year', $oldAdmission->admission_year)->first();
                foreach ($oldClass as $class) {
                    $oldStudent = DB::connection('old_db')->table('students')
                        ->join('class_students', 'students.id', '=', 'class_students.student_id')
                        ->join('student_infos', 'students.id', '=', 'student_infos.student_id')
                        ->where('class_students.class_id', $class->id)
                        ->select('students.*', 'student_infos.*')
                        ->get();

                    $newClass = ClassGenerate::create([
                        'name' => $class->code,
                        'code' => $class->code,
                        'type' => $class->type,
                        'status' => 'enable' === $class->status ? Status::Active->value : Status::Inactive->value,
                        'faculty_id' => $class->faculty_id,
                        'admission_year_id' => $admissionYear->id,
                        'description' => $class->name,
                    ]);

                    foreach ($oldStudent as $student) {

                        $gender = $student->gender;
                        if ('unspecified' === $gender) {
                            $gender = Gender::Male->value;
                        }

                        $newStudent = Student::updateOrCreate([
                            'code' => $student->code,
                        ], [
                            'code' => $student->code,
                            'full_name' => $student->last_name . ' ' . $student->first_name,
                            'gender' => $gender,
                            'dob' => $student->dob,
                            'phone' => $student->phone,
                            'address' => $student->address,
                            'status' => $student->status,
                            'faculty_id' => $student->faculty_id,
                            'admission_year_id' => $admissionYear->id,
                            'first_name' => $student->first_name,
                            'last_name' => $student->last_name,
                            'email' => $student->email,
                            'person_email' => $student->person_email,
                            'email_edu' => $student->email,
                            'school_year_start' => $admissionYear->school_year,
                            'school_year_end' => $admissionYear->school_year + 4,
                            'permanent_residence' => $student->permanent_residence,
                            'pob' => $student->pob,
                            'countryside' => $student->countryside,
                            'nationality' => $student->nationality,
                            'ethnic' => $student->ethnic,
                            'religion' => $student->religion,
                            'note' => $student->note,
                        ]);

                        $oldFamily = DB::connection('old_db')->table('families')->where('student_id', $student->id)->get();
                        foreach ($oldFamily as $family) {
                            $newStudent->families()->updateOrCreate([
                                'student_id' => $newStudent->id,
                                'relationship' => $family->relationship,
                            ], [
                                'full_name' => $family->full_name,
                                'relationship' => $family->relationship,
                                'phone' => $family->phone,
                                'job' => $family->job,
                            ]);
                        }

                        $newStudent->classes()->sync($newClass->id);
                    }
                }
                echo 'Admission year ' . $oldAdmission->admission_year . ' synced successfully' . PHP_EOL;
            }
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}

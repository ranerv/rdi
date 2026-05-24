<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\College;
use App\Models\Department;

class CollegeDepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $colleges = [
            ['name' => 'College of Engineering', 'code' => 'COE'],
            ['name' => 'College of Science', 'code' => 'COS'],
            ['name' => 'College of Arts and Sciences', 'code' => 'CAS'],
            ['name' => 'College of Business and Economics', 'code' => 'CBE'],
            ['name' => 'College of Education', 'code' => 'COE-ED'],
            ['name' => 'College of Health Sciences', 'code' => 'CHS'],
            ['name' => 'College of Agriculture', 'code' => 'CAG'],
            ['name' => 'College of Law', 'code' => 'COLAW'],
        ];

        $departments = [
            'COE' => ['Civil Engineering', 'Mechanical Engineering', 'Electrical Engineering'],
            'COS' => ['Physics', 'Chemistry', 'Biology'],
            'CAS' => ['English', 'Mathematics', 'History'],
            'CBE' => ['Accounting', 'Management', 'Economics'],
            'COE-ED' => ['Teacher Education', 'Curriculum Development', 'Educational Psychology'],
            'CHS' => ['Nursing', 'Medicine', 'Public Health'],
            'CAG' => ['Plant Science', 'Animal Science', 'Agricultural Economics'],
            'COLAW' => ['Civil Law', 'Criminal Law', 'International Law'],
        ];

        foreach ($colleges as $collegeData) {
            $college = College::firstOrCreate(
                ['code' => $collegeData['code']],
                ['name' => $collegeData['name']]
            );

            // Create departments for this college
            if (isset($departments[$collegeData['code']])) {
                foreach ($departments[$collegeData['code']] as $departmentName) {
                    Department::firstOrCreate(
                        [
                            'college_id' => $college->id,
                            'name' => $departmentName,
                        ]
                    );
                }
            }
        }
    }
}

<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\TrainingIndustry;
use Illuminate\Database\Seeder;

class TrainingIndustrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facultyIdInformationTechnology = 1;
        $trainingIndustries = [
            [
                'code' => '7480201',
                'name' => 'Công nghệ thông tin',
                'description' => 'Ngành đào tạo về công nghệ thông tin, lập trình, phát triển phần mềm',
                'faculty_id' => $facultyIdInformationTechnology,
            ],
            [
                'code' => '7480202',
                'name' => 'Mạng máy tính và truyền thông dữ liệu',
                'description' => 'Ngành đào tạo về mạng máy tính, truyền thông dữ liệu, an toàn thông tin',
                'faculty_id' => $facultyIdInformationTechnology,
            ],
            [
                'code' => '7480203',
                'name' => 'Kỹ thuật phần mềm',
                'description' => 'Ngành đào tạo về kỹ thuật phần mềm, lập trình, phát triển phần mềm',
                'faculty_id' => $facultyIdInformationTechnology,
            ],
        ];

        foreach ($trainingIndustries as $industry) {
            if ($industry['faculty_id'] === $facultyIdInformationTechnology) {
                $this->checkIssetBeforeCreate($industry);
            }
        }
    }

    /**
     * Kiểm tra và tạo hoặc cập nhật dữ liệu
     */
    private function checkIssetBeforeCreate(array $data): void
    {
        $trainingIndustry = TrainingIndustry::where('code', $data['code'])->first();

        if (empty($trainingIndustry)) {
            TrainingIndustry::create($data);
        } else {
            $trainingIndustry->update($data);
        }
    }
}

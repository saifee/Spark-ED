<?php

namespace App\Console\Commands;

use App\Models\Academic\AcademicSession;
use App\Models\Configuration\Behaviour\EmployeeSkill;
use App\Models\Configuration\Behaviour\Skill;
use App\Models\Configuration\Behaviour\SkillIcon;
use App\Repositories\Academic\BatchRepository;
use Illuminate\Console\Command;

class BehaviourInit extends Command
{
    protected $academic_session;
    protected $batch;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'behaviour';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        AcademicSession $academic_session,
        BatchRepository $batch
    ) {
        $this->academic_session = $academic_session;
        $this->batch = $batch;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $default_academic_session = optional($this->academic_session->whereIsDefault(1)->first())->id;
        if (!$default_academic_session) {
            $this->error('No academic session found.');
        }

        $configurations = getSeedVar('behaviour');

        $skill_icons = $configurations['skill_icons'];
        foreach ($skill_icons as $skill_icon) {
            SkillIcon::firstOrCreate(['name' => $skill_icon]);
        }


        $skills = $configurations['skills'];

        $batches = $this->batch->getQuery()->whereHas('course', function ($q1) use ($default_academic_session) {
            $q1->where('academic_session_id', '=', $default_academic_session);
        })->select('id')->get();

        foreach ($batches as $batch) {
            foreach ($skills as $skill) {
                Skill::firstOrCreate([
                    'academic_session_id' => $default_academic_session,
                    'batch_id' => $batch->id,
                    'name' => $skill['name'],
                ], [
                    'points' => $skill['points'],
                    'positive' => $skill['positive'],
                    'skill_icon_id' => optional(SkillIcon::where('name',$skill['icon'])->first())->id,
                    'options' => [],
                ]);
            }
        }

        $employee_skills = $configurations['employee_skills'];

        foreach ($batches as $batch) {
            foreach ($employee_skills as $skill) {
                EmployeeSkill::firstOrCreate([
                    'name' => $skill['name'],
                ], [
                    'points' => $skill['points'],
                    'positive' => $skill['positive'],
                    'skill_icon_id' => optional(SkillIcon::where('name',$skill['icon'])->first())->id,
                    'options' => [],
                ]);
            }
        }

        $this->info('Done.');
    }
}

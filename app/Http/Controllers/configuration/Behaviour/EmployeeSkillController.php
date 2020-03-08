<?php

namespace App\Http\Controllers\Configuration\Behaviour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Behaviour\EmployeeSkillRequest;
use App\Models\Configuration\Behaviour\EmployeeSkill;
use App\Repositories\Configuration\Behaviour\EmployeeSkillRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmployeeSkillController extends Controller
{
    protected $request;
    protected $repo;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct(
        Request $request,
        EmployeeSkillRepository $repo
    ) {
        $this->request = $request;
        $this->repo = $repo;

        $this->middleware('permission:access-configuration');
    }

    /**
     * Used to get all EmployeeSkill
     * @get ("/api/employee-skill")
     * @return Response
     */
    public function index()
    {
        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to print all EmployeeSkill
     * @post ("/api/employee-skill/print")
     * @return Response
     */
    public function print()
    {
        $employee_skills = $this->repo->print(request('filter'));

        return view('print.behaviour.employee_skill', compact('employee_skills'))->render();
    }

    /**
     * Used to generate pdf all EmployeeSkill
     * @post ("/api/employee-skill/pdf")
     * @return Response
     */
    public function pdf()
    {
        $employee_skills = $this->repo->print(request('filter'));

        $uuid = Str::uuid();
        $pdf = \PDF::loadView('print.behaviour.employee_skill', compact('employee_skills'))->save('../storage/app/downloads/'.$uuid.'.pdf');

        return $uuid;
    }

    /**
     * Used to store EmployeeSkill
     * @post ("/api/employee-skill")
     * @param ({
     *      @Parameter("name", type="string", required="true", description="Name of EmployeeSkill"),
     *      @Parameter("description", type="text", required="true", description="Description of EmployeeSkill"),
     * })
     * @return Response
     */
    public function store(EmployeeSkillRequest $request)
    {
        $employee_skill = $this->repo->create($this->request->all());

        return $this->success(['message' => trans('behaviour.employee_skill_added')]);
    }

    /**
     * Used to get EmployeeSkill detail
     * @get ("/api/employee-skill/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of EmployeeSkill"),
     * })
     * @return Response
     */
    public function show($id)
    {
        return $this->ok($this->repo->findOrFail($id));
    }

    /**
     * Used to update EmployeeSkill
     * @patch ("/api/employee-skill/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of EmployeeSkill"),
     *      @Parameter("name", type="string", required="true", description="Name of EmployeeSkill"),
     *      @Parameter("description", type="text", required="true", description="Description of EmployeeSkill"),
     * })
     * @return Response
     */
    public function update($id, EmployeeSkillRequest $request)
    {
        $employee_skill = $this->repo->findOrFail($id);

        $employee_skill = $this->repo->update($employee_skill, $this->request->all());

        return $this->success(['message' => trans('behaviour.employee_skill_updated')]);
    }

    /**
     * Used to delete EmployeeSkill
     * @delete ("/api/employee-skill/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of EmployeeSkill"),
     * })
     * @return Response
     */
    public function destroy($id)
    {
        $employee_skill = $this->repo->deletable($id);

        $this->repo->delete($employee_skill);

        return $this->success(['message' => trans('behaviour.employee_skill_deleted')]);
    }
}

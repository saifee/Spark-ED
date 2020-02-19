<?php

namespace App\Http\Controllers\Configuration\Behaviour;

use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Behaviour\SkillRequest;
use App\Models\Configuration\Behaviour\Skill;
use App\Repositories\Configuration\Behaviour\SkillRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SkillController extends Controller
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
        SkillRepository $repo
    ) {
        $this->request = $request;
        $this->repo = $repo;

        $this->middleware('permission:access-configuration');
        $this->middleware('academic.session.set');
    }

    /**
     * Used to get all Behaviour Course Categories
     * @get ("/api/skill")
     * @return Response
     */
    public function index()
    {
        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to print all Behaviour Course Categories
     * @post ("/api/skill/print")
     * @return Response
     */
    public function print()
    {
        $categories = $this->repo->print(request('filter'));

        return view('print.behaviour.skill', compact('categories'))->render();
    }

    /**
     * Used to generate pdf all Behaviour Course Categories
     * @post ("/api/skill/pdf")
     * @return Response
     */
    public function pdf()
    {
        $categories = $this->repo->print(request('filter'));

        $uuid = Str::uuid();
        $pdf = \PDF::loadView('print.behaviour.skill', compact('categories'))->save('../storage/app/downloads/'.$uuid.'.pdf');

        return $uuid;
    }

    /**
     * Used to store Behaviour Skill
     * @post ("/api/skill")
     * @param ({
     *      @Parameter("name", type="string", required="true", description="Name of Skill"),
     *      @Parameter("description", type="text", required="true", description="Description of Skill"),
     * })
     * @return Response
     */
    public function store(SkillRequest $request)
    {
        $skill = $this->repo->create($this->request->all());

        return $this->success(['message' => trans('behaviour.skill_added')]);
    }

    /**
     * Used to get Behaviour Skill detail
     * @get ("/api/skill/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Behaviour Skill"),
     * })
     * @return Response
     */
    public function show($id)
    {
        return $this->ok($this->repo->findOrFail($id));
    }

    /**
     * Used to update Behaviour Skill
     * @patch ("/api/skill/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Behaviour Skill"),
     *      @Parameter("name", type="string", required="true", description="Name of Skill"),
     *      @Parameter("description", type="text", required="true", description="Description of Skill"),
     * })
     * @return Response
     */
    public function update($id, SkillRequest $request)
    {
        $skill = $this->repo->findOrFail($id);

        $skill = $this->repo->update($skill, $this->request->all());

        return $this->success(['message' => trans('behaviour.skill_updated')]);
    }

    /**
     * Used to delete Behaviour Skill
     * @delete ("/api/skill/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Behaviour Skill"),
     * })
     * @return Response
     */
    public function destroy($id)
    {
        $skill = $this->repo->deletable($id);

        $this->repo->delete($skill);

        return $this->success(['message' => trans('behaviour.skill_deleted')]);
    }
}

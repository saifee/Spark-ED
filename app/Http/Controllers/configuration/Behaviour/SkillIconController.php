<?php

namespace App\Http\Controllers\Configuration\Behaviour;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Configuration\Behaviour\SkillIconRequest;
use App\Repositories\Configuration\Behaviour\SkillIconRepository;

class SkillIconController extends Controller
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
        SkillIconRepository $repo
    ) {
        $this->request = $request;
        $this->repo = $repo;

        $this->middleware('permission:access-configuration');
    }

    /**
     * Used to get all Skill Icons
     * @get ("/api/behaviour/skill/icon")
     * @return Response
     */
    public function index()
    {
        return $this->ok($this->repo->paginate($this->request->all()));
    }

    /**
     * Used to print all Skill Icons
     * @post ("/api/behaviour/skill/icon/print")
     * @return Response
     */
    public function print()
    {
        $skill_icons = $this->repo->print(request('filter'));

        return view('print.configuration.behaviour.skill-icon', compact('skill_icons'))->render();
    }

    /**
     * Used to generate pdf all Skill Icons
     * @post ("/api/behaviour/skill/icon/pdf")
     * @return Response
     */
    public function pdf()
    {
        $skill_icons = $this->repo->print(request('filter'));

        $uuid = Str::uuid();
        $pdf = \PDF::loadView('print.configuration.behaviour.skill-icon', compact('skill_icons'))->save('../storage/app/downloads/'.$uuid.'.pdf');

        return $uuid;
    }

    /**
     * Used to store Skill Icon
     * @post ("/api/behaviour/skill/icon")
     * @param ({
     *      @Parameter("name", type="string", required="true", description="Name of Skill Icon"),
     *      @Parameter("description", type="text", required="optional", description="Description of Skill Icon")
     * })
     * @return Response
     */
    public function store(SkillIconRequest $request)
    {
        $skill_icon = $this->repo->create($this->request->all());

        return $this->success(['message' => trans('behaviour.skill_icon_added')]);
    }

    /**
     * Used to get Skill Icon detail
     * @get ("/api/behaviour/skill/icon/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Skill Icon"),
     * })
     * @return Response
     */
    public function show($id)
    {
        return $this->ok($this->repo->findOrFail($id));
    }

    /**
     * Used to update Skill Icon
     * @patch ("/api/behaviour/skill/icon/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Skill Icon"),
     *      @Parameter("name", type="string", required="true", description="Name of Skill Icon"),
     *      @Parameter("description", type="text", required="optional", description="Description of Skill Icon")
     * })
     * @return Response
     */
    public function update($id, SkillIconRequest $request)
    {
        $skill_icon = $this->repo->findOrFail($id);

        $skill_icon = $this->repo->update($skill_icon, $this->request->all());

        return $this->success(['message' => trans('behaviour.skill_icon_updated')]);
    }

    /**
     * Used to delete Skill Icon
     * @delete ("/api/behaviour/skill/icon/{id}")
     * @param ({
     *      @Parameter("id", type="integer", required="true", description="Id of Skill Icon"),
     * })
     * @return Response
     */
    public function destroy($id)
    {
        $skill_icon = $this->repo->deletable($id);

        $this->repo->delete($skill_icon);

        return $this->success(['message' => trans('behaviour.skill_icon_deleted')]);
    }
}

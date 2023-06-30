<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectRequest;
use App\Services\ProjectRequest\ProjectRequestService;
use App\Trait\HandleResponse;
use Illuminate\Support\Facades\Log;



class ProjectRequestController extends Controller
{
    use HandleResponse;

    public $projectRequestService;

    public function __construct(ProjectRequestService $projectRequestService)
    {
        $this->projectRequestService = $projectRequestService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,)
    {
        $perPage = ($request->perPage) ?? 10;

        $projects = ProjectRequest::where([
            'user_id' => $request->user()->id,
            'company_id' => getActiveWorkSpace()->id,
        ])
        ->with(['uploadedFiles', 'pm', 'designer', 'projectUser', 'brand', 'created_by']);

        ($request->todo) ? $projects =  $projects->where('status', 'todo') :"";
        ($request->on_going) ? $projects =  $projects->where('status', 'on_going') :"";
        ($request->in_review) ? $projects =  $projects->where('status', 'in_review'):"";
        ($request->approved) ? $projects =  $projects->where('status', 'designer_approved')->orWhere('status', 'pm_approved')->orWhere('status', 'completed'):"";
        ($request->perPage) ? $projects =  $projects->paginate($perPage) : $projects = $projects->get();

        return $this->successResponse($projects, 'Projects Fetched Succesfully', 200);
    }

    public function getCompanyRequests(Request $request,)
    {
        $perPage = ($request->perPage) ?? 10;

        $projects = ProjectRequest::where([
            'company_id' => $request->company_id,
        ])
        ->with(['uploadedFiles', 'pm', 'designer', 'projectUser', 'brand', 'created_by'])->orderBy('created_at', 'desc');

        ($request->todo) ? $projects =  $projects->where('status', 'todo') :"";
        ($request->on_going) ? $projects =  $projects->where('status', 'on_going') :"";
        ($request->in_review) ? $projects =  $projects->where('status', 'in_review'):"";
        ($request->approved) ? $projects =  $projects->where('status', 'designer_approved')->orWhere('status', 'pm_approved')->orWhere('status', 'completed'):"";
        ($request->perPage) ? $projects =  $projects->paginate($perPage) : $projects = $projects->get();

        return $this->successResponse($projects, 'Projects Fetched Succesfully', 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'dimension' => 'required',
            'colors' => 'required',
            // 'deliverables' => 'required',
            'attachments.*' => 'mimes:jpg,jpeg,png,svg,pdf,eps,gif,adobe|max:5000',
        ]);

        return $this->projectRequestService->createProjectRequest($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectRequest  $projectRequest
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project =  ProjectRequest::with(['brand','projectMessage','uploadedFiles', 'pm', 'designer'])
        // ->whereHas('brand', function($q){
        //     $q->whereCompanyId(getActiveWorkSpace()->id);
        // })
        ->whereId($id)->firstOrFail();

        return $this->successResponse( $project, 'Project fetched successfully', 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectRequest  $projectRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $project =  ProjectRequest::whereId($id)->firstOrFail();

        tap( $project, function ($collection) use ( $project, $request) {
             $project->fill(
                $request->only([
                    'brand_id',
                    'title',
                    'description',
                    'dimension',
                    'colors',
                    'deliverables',
                ])
            );
            return $collection->save();
        });

        return $this->successResponse($project, 'Project updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectRequest  $projectRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $projectRequest = ProjectRequest::find($id);

        if($projectRequest){
            if($projectRequest->status == "todo"){
                $deleted = $projectRequest->delete();
                return $this->successResponse($deleted, 'Project deleted successfully', 200);
            }
        return abort(422, 'The request is not processable.');

        }
        return $this->errorResponse($projectRequest);

    }


}

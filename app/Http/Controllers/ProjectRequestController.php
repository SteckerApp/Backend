<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectRequest;
use App\Services\ProjectRequest\ProjectRequestService;
use App\Trait\HandleResponse;


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
        $projects = ProjectRequest::where('user_id', auth()->user()->id);
        ($request->todo) ? $projects =  $projects->where('status', 'pending') :"";
        ($request->ongoing) ? $projects =  $projects->where('status', 'on-going') :"";
        ($request->in_review) ? $projects =  $projects->where('status', 'designer-approved'):"";
        ($request->approved) ? $projects =  $projects->where('status', 'pm-approved'):"";
        ($request->page) ? $projects =  $projects->paginate($perPage) : $projects = $projects->get();

        return $this->successResponse($projects, '', 200);
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
            'brand_id' => 'required',
            'user_id' => 'required',
            'subscription_id' => 'required',
            'title' => 'required',
            'description' => 'required',
            'dimension' => 'required',
            'colors' => 'required',
            'deliverables' => 'required',
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
        $project =  ProjectRequest::where('id',$id)->first();

        if( $project){
            return $this->successResponse($project);
        }

        return $this->errorResponse($brand);

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
        $project =  ProjectRequest::where('id',$id)->update(
            [
                'brand_id' => $request->brand_id ?? $request->brand_id,
                'title' => $request->title,
                'description' => $request->description ?? $request->description,
                'dimension' => json_encode($request->dimension),
                'colors' => json_encode($request->colors),
                'deliverables' => $request->deliverables,
            ]
        );

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
            $deleted = $projectRequest->delete();
            return $this->successResponse($deleted, 'Project deleted successfully', 200);
        }
        return $this->errorResponse($projectRequest);

    }
}

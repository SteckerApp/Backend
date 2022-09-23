<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProjectDeliverable;
use App\Services\ProjectRequest\ProjectRequestService;
use App\Trait\HandleResponse;



class ProjectDeliverablesController extends Controller
{
    use HandleResponse;

    public $projectRequestService;

    public function __construct(ProjectRequestService $projectRequestService)
    {
        $this->projectRequestService = $projectRequestService;
    }

    public function index(Request $request)
    {

       $data = ProjectDeliverable::select('id', 'project_id', 'location','created_at')->where('project_id', $request->project_id)->orderBy('created_at', 'desc')->get();

        return $this->successResponse($data, 'success', 200);
    }

    public function uploadDeliverables(Request $request)
    {
        $this->validate($request, [
            'project_id' => 'required',
            'attachments.*' => 'mimes:jpg,jpeg,png,svg,pdf,eps,gif,adobe|max:5000',
        ]);

        return $this->projectRequestService->uploadDeliverables($request);
    }


    public function destroy(Request $request, $id)
    {
        $data = ProjectDeliverable::whereId($id)->firstOrFail();
        $data->delete();

        return $this->successResponse(true, 'Deleted successfully', 200);
    }
}

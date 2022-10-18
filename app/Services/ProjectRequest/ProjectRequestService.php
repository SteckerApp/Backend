<?php
namespace App\Services\ProjectRequest;

use Carbon\Carbon;
use App\Models\ProjectUser;
use App\Trait\HandleResponse;
use App\Models\ProjectRequest;
use App\Models\ProjectDeliverable;
use App\Events\NewProjectRequestCreated;

class ProjectRequestService
{
    use HandleResponse;

    public function createProjectRequest($request)
    {

        $user = auth()->user();
        $attachments = [];


        if($request->hasfile('attachments'))
         {
            foreach($request->file('attachments') as $key => $file)
            {
                $key++;
                $path = "/".$user->id."/projects/attachments/".$request->title."/";
                $name = $file->getClientOriginalName();
                $doc_link = uploadDocument($file, $path, $name);
                array_push($attachments, $doc_link);
            }
         }

        $project = ProjectRequest::create([
            'brand_id' => $request->brand_id,
            'user_id' => $user->id,
            'subscription_id' => $request->subscription_id,
            'title' => $request->title,
            'description' => $request->description,
            'dimension' => json_encode($request->dimension),
            'example_links' => $request->example_links,
            'example_uploads' => (count($attachments) > 0) ? json_encode($attachments) : null,
            'colors' => json_encode($request->colors),
            'deliverables' => json_encode($request->deliverables),
            'date' => Carbon::now(),

        ]);

        $project = ProjectUser::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);

        // event(new NewProjectRequestCreated($project));

        return $this->successResponse($project, 'Project created successfully', 201);

    }
    public function uploadDeliverables($request)
    {

        $user = auth()->user();


        if($request->hasfile('attachments'))
         {
            foreach($request->file('attachments') as $key => $file)
            {
                $key++;
                $path = "/".$user->id."/projects/deliverables/".$request->title;
                $name = $file->getClientOriginalName();
                $doc_link = uploadDocument($file, $path, $name);

                $record = ProjectDeliverable::create([
                    'project_id' => $request->project_id,
                    'location' => $doc_link,
                    'user_id' => auth()->user()->id,
                ]);
            }
            return $this->successResponse(true, 'Project uploaded successfully', 201);

         }

        return $this->errorResponse(true, 'No deliverables attached');
    }
}
?>

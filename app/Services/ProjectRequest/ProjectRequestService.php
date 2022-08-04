<?php
namespace App\Services\ProjectRequest;

use Carbon\Carbon;
use App\Trait\HandleResponse;
use App\Models\ProjectRequest;

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
                $path = "/".$user->id."/projects/".$request->title."/example_uploads/";
                $name = $file->getClientOriginalName();
                $doc_link = uploadDocument($file, $path, $name);
                array_push($attachments, $doc_link);
            }
         }

        $record = ProjectRequest::create([
            'brand_id' => $request->brand_id,
            'user_id' => $user->id,
            'subscription_id' => $request->subscription_id,
            'title' => $request->title,
            'description' => $request->description,
            'dimension' => json_encode($request->dimension),
            'example_links' => $request->example_links,
            'example_uploads' => (count($attachments) > 0) ? json_encode($attachments) : null,
            'colors' => json_encode($request->colors),
            'deliverables' => $request->deliverables,
            'date' => Carbon::now(),

        ]);

        return $this->successResponse($record, 'Project created successfully', 201);

    }
}
?>

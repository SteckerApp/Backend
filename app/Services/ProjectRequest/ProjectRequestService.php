<?php
namespace App\Services\ProjectRequest;

use App\Events\MessageSent;
use Carbon\Carbon;
use App\Models\ProjectUser;
use App\Trait\HandleResponse;
use App\Models\ProjectRequest;
use App\Models\ProjectDeliverable;
use App\Events\NewProjectRequestCreated;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\DB;
use App\Models\CompanySubscription;
use App\Models\ProjectMessage;

class ProjectRequestService
{
    use HandleResponse;

    public function createProjectRequest($request)
    {

        $user = auth()->user();
        $attachments = [];
        $company_id =  getActiveWorkSpace($request->user()->id)->id;
        // $user_id = $request->user()->id;
        $subscription_id = CompanySubscription::where([
            'company_id' => $company_id,
            'status' => 'active', 
            'payment_status' => 'paid'
            ])->first()->subscription_id;

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
         //manage example upload incase of duplicates
         $example_uploads = [];
         if(count($attachments) > 0 && !$request->example_uploads){
            $example_uploads = $attachments;
         }
         elseif(count($attachments) > 0 && $request->example_uploads){
            foreach(json_decode($request->example_uploads) as $upload){
                array_push($attachments,$upload);
            }
            $example_uploads = $attachments;
         }
         else{
            $example_uploads = $request->example_uploads;
         }

        $project = ProjectRequest::create([
            'brand_id' => $request->brand_id,
            'user_id' => $user->id,
            'subscription_id' =>  $subscription_id,
            'title' => $request->title,
            'description' => $request->description,
            'dimension' => $request->dimension,
            'company_id' => getActiveWorkSpace($request->user()->id)->id,
            'pm_id' => getActiveWorkSpace($request->user()->id)->account_manager,
            'example_links' => $request->example_links ? $request->example_links : null,
            'example_uploads' => json_encode($example_uploads),
            'colors' => $request->colors,
            'created_by' => $request->user()->id,
            'deliverables' => $request->deliverables ?  $request->deliverables : null,
            'date' => Carbon::now()
        ]);
        //add user to project user
         DB::table('project_user')->insert([
            'project_id' => $project->id,
            'user_id' => $user->id,
        ]);
        //add description to project messages
       $project_message = ProjectMessage::create([
            'user_id' => $request->user()->id,
            "project_id"=>  $project->id,
            "message"=>  $request->description,
        ]);

        
        if($example_uploads)
        {
            $type = 'attachment';
           foreach($example_uploads as $path)
           {
            $pathInfo = pathinfo($path);
            $fileName = $pathInfo['filename'];
            //add files to deliverables
               ProjectDeliverable::create([
                   "project_id"=>  $project->id,
                   "project_message_id"=>  $project_message->id,
                   "title"=>  $fileName,
                   "location"=>  $path, 
                'user_id' => $request->user()->id,
               ]);
            // //add files to project messages
            // ProjectMessage::create([
            //     'user_id' => $request->user()->id,
            //     "project_id"=>  $project->id
            // ]);
           }
        }
        else{
            $type = 'comment';

        }
        // $project_users = DB::table('project_user')->where('project_id', $project->id )->where('user_id', '!=', $request->user()->id)->get();

        // dd($project->id);


        // foreach($project_users as $project_user){


            $data = [
                'user_id' => getActiveWorkSpace($request->user()->id)->account_manager,
                'type' => $type,
                'project_id' => $project->id,
                'project_message_id' => $project_message->id,
                'commenter_id' => null
            ];
            $notification = new NotificationController();
            $notification->store($data);
        // }

        
        // event(new MessageSent($project_message));

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
                    'project_message_id' => $request->project_message_id,
                    'location' => $doc_link,
                    'title' => $name,
                    'user_id' => auth()->user()->id,
                ]);
            }
            return $this->successResponse(true, 'Project uploaded successfully', 201);

         }

        return $this->errorResponse(true, 'No deliverables attached');
    }
}
?>

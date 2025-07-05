<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\ViewProjectsRequest;
use App\Http\Requests\StoreCVRequest;
use App\Http\Requests\VieweCVRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\CVResource;
use App\Models\Project;
use App\Models\Cv;
use App\Traits\Uploadfile;


class ProjectController extends BaseController
{
    use Uploadfile;

      public function store(StoreProjectRequest $request)
        {
             $data = $request->validated();
             if ($request->hasFile('image_path'))
             {
              $path = $this->storeFile($request->file('image_path'),'projects', null,'public_uploads');
              $data['image_path'] = asset('uploads/' . $path);
             }
             $project = $request->user()->projects()->create($data);
             return $this->sendResponse(new ProjectResource($project), 'Project created successfully', 201);
        }
      
      public function index()
        {
          $projects = Project::all();
          return $this->sendResponse(ProjectResource::collection($projects), "Successfully retrieved all Project.");
        }

      public function storeCv(StoreCVRequest $request)
        {
          $data = $request->validated();
          if ($request->hasFile('image_path'))
         {
         $path = $this->storeFile($request->file('image_path'),'projects', null,'public_uploads');
         $data['image_path'] = asset('uploads/' . $path);
         }
         $project = $request->user()->cvs()->create($data);
          return $this->sendResponse(new CvResource($project), 'CV created successfully', 201);
        }


       public function indexCv(VieweCVRequest $request)
       {
         $CV = Cv::all();
         return $this->sendResponse(CVResource::collection($CV), "Successfully retrieved all CV.");
       }
}

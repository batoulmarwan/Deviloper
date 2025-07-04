<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\ViewProjectsRequest;
use App\Http\Requests\StoreCVRequest;
use App\Http\Requests\VieweCVRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\CVResource;

class ProjectController extends BaseController
{
  
   public function store(StoreProjectRequest $request)
   {
       $project = $request->user()->projects()->create($request->validated());
       return response()->json(new ProjectResource($project),201);
   }

  
   public function index(ViewProjectsRequest $request)
   {
    $projects = $request->user()->projects()->get();
    return ProjectResource::collection($projects);
   }
  
   public function storeCv(StoreCVRequest $request)
   {
    $project = $request->user()->cvs()->create($request->validated());
    return response()->json(new CVResource($project),201);
   }


   public function indexCv(VieweCVRequest $request)
   {
    $projects = $request->user()->cvs()->get();
    return CVResource::collection($projects);
   }
}

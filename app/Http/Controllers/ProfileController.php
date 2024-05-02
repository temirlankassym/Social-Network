<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\AwsService;
use Illuminate\Http\Request;
use App\Models\Profile;

class ProfileController extends Controller
{
    private AwsService $awsService;

    public function __construct(AwsService $awsService)
    {
        $this->awsService = $awsService;
    }

    public function index()
    {
        return response()->json(["ID"=>auth()->user()->id]);
    }

    public function update(ProfileUpdateRequest $request)
    {
        $data = $request->validated();

        $file = $request->file('image');

        if($file){
            $data['image'] = $this->awsService->store($file);
        }

        auth()->user()->profile->update($data);

        return response("Success",200);
    }
}

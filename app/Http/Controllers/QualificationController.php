<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQualificationRequest;
use App\Http\Requests\UpdateQualificationRequest;
use App\Models\Qualification;
use Illuminate\Http\Request;

class QualificationController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->userDetails;
        return response()->json([
            'error' => false,
            'message' => 'All Qualifications show',
            'data' => $doctor->qualifications
        ]);
    }

    public function store(StoreQualificationRequest $request)
    {
        $qualification = Qualification::create([
            'degree_id' => $request->input('degree_id'),
            'institution_id' => $request->input('institution_id'),
            'major' => $request->input('major'),
            'from_date' => $request->input('from_date'),
            'to_date' => $request->input('to_date'),
            'doctor_id' => auth()->user()->userDetails->id
        ]);
        return response()->json([
            'error' => 'false',
            'message' => 'Qualification added successfully!!',
            'data' => $qualification
        ], 201);
    }

    public function update(UpdateQualificationRequest $request, $id)
    {
        $qualification = Qualification::find($id);
        if($qualification)
        {
            if(auth()->user()->hasRole('doctor'))
            {
                $data = [
                    'degree_id' => $request->input('degree_id'),
                    'institution_id' => $request->input('institution_id'),
                    'major' => $request->input('major'),
                    'from_date' => $request->input('from_date'),
                    'to_date' => $request->input('to_date')
                ];
                if($data)
                {
                    $qualification->update($data);
                    return response()->json([
                        'error' => false,
                        'message' => 'Qualification updated successfully!!',
                        'data' => $qualification
                    ]);
                }
                else
                {
                    return response()->json([
                        'error' => true,
                        'message' => 'There is no data to update!!'
                    ], 400);
                }
            }
            else
            {
                return response()->json([
                    'error' => true,
                    'message' => 'Unauthorize access!!'
                ], 401);
            }
        }
        else
        {
            return response()->json([
                'error' => true,
                'message' => 'Qualification not found'
            ], 404);
        }

    }

    public function delete($id)
    {
        $qualification = Qualification::find($id);
        if($qualification)
        {
            if(auth()->user()->hasRole('doctor'))
            {
                $qualification->delete();
                return response()->json([
                    'error' => false,
                    'message' => 'Qualification deleted successfully!!',
                    'data' => $qualification
                ]);
            }
            else
            {
                return response()->json([
                    'error' => true,
                    'message' => 'Unauthorize access!!'
                ], 401);
            }
        }
        else
        {
            return response()->json([
                'error' => true,
                'message' => 'Qualification  not found'
            ], 404);
        }
    }
}

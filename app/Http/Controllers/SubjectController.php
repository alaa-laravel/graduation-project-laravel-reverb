<?php

namespace App\Http\Controllers;

use App\Models\subject;
use App\Http\Requests\StoresubjectRequest;
use App\Http\Requests\UpdatesubjectRequest;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = Subject::select('id', 'name', 'grade_level_id')
            ->with(['gradelevel:id,grade_level'])
            ->get();

        return response()->json([

            'status' => 200,
            'message' => "all subjects with its grade level",
            'data' => $subjects,
        ]);
    }




    public function store(StoresubjectRequest $request)
    {
        $subject = $request->validated();
        $subject = subject::create($subject);

        return response()->json([
            'status' => 200,
            'message' => 'subject created successfully',
            'data' => $subject,
        ]);
    }

    public function destroy($id)
    {
        $subject = subject::find($id);

        if (!$subject) {
            return response()->json([
                'status' => 404,
                'message' => 'subject not found',
            ]);
        }

        $subject->delete();

        return response()->json([
            'status' => 200,
            'message' => 'subject deleted successfully',
        ]);
    }
}

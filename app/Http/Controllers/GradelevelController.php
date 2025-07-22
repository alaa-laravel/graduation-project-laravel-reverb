<?php

namespace App\Http\Controllers;

use App\Models\gradelevel;
use App\Http\Requests\StoregradelevelRequest;
use App\Http\Requests\UpdategradelevelRequest;

class GradelevelController extends Controller
{

    public function index()
    {
        $all_grade_levels = Gradelevel::select('id', 'grade_level')
            ->with('users:id,name,grade_level_id')
            ->get();

        return response()->json([
            'status' => 200,
            'message' => 'All grade levels retrieved successfully.',
            'data' => $all_grade_levels
        ]);
    }


    public function store(StoregradelevelRequest $request)
    {
        $grade_level = $request->validated();

        $grade_level = gradelevel::create($grade_level);

        return response()->json([
            'status' => 200,
            'message' => 'grade level created successfully',
            'data' => $grade_level,
        ]);
    }

    public function show(gradelevel $gradelevel)
    {
        $gradelevel->load([
            'users:id,name,grade_level_id',
            'subjects:id,name,grade_level_id'
        ]);

        return response()->json([
            'status' => 200,
            'message' => 'Grade level details retrieved successfully',
            'data' => $gradelevel
        ]);
    }

    public function destroy($id)
    {
        $gradelevel = gradelevel::find($id);

        if (!$gradelevel) {
            return response()->json([
                'status' => 404,
                'message' => 'grade level not found',
            ]);
        }

        $gradelevel->delete();

        return response()->json([
            'status' => 200,
            'message' => 'grade level deleted successfully',
        ]);
    }
}

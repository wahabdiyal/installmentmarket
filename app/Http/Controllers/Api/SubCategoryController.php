<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ctg = Category::whereNotNull('parent_id')
            ->select('id', 'name')
            ->get();
        if (!$ctg->isEmpty()) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Sub_Categories list',
                    'data' => $ctg,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No Sub_Category found',
                ],
                404,
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ctg = Validator::make($request->all(), [
            'name' => 'required',
            'parent_id'=>'required'
        ]);

        if ($ctg->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $ctg->errors(),
                ],
                401,
            );
        }
        $category = Category::create([
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return response()->json(
            [
                'status' => true,
                'message' => 'Sub-Category Created Successfully',
                'data' => $category,
            ],
            200,
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ctg = Category::whereNotNull('parent_id')
            ->select('id', 'name')
            ->where('id', $id)
            ->first();
        if ($ctg) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Sub-Category Found',
                    'data' => $ctg,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No Sub-Category found',
                ],
                404,
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ctg = Validator::make($request->all(), [
            'name' => 'required',

        ]);

        if ($ctg->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $ctg->errors(),
                ],
                401,
            );
        }
        $category = Category::where('id',$id)->whereNotNull('parent_id')->first();
        if ($category) {
            $category->update([
                'name' => $request->name,
            ]);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Sub-Category Update Successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Sub-Category Not Found',
                ],
                200,
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ctg = Category::where('id', $id)->whereNotNull('parent_id')->first();
        if (!$ctg) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Not Category found',
                ],
                404,
            );
        }



        $ctg_del = $ctg->delete();

        if ($ctg_del) {


            return response()->json(
                [
                    'status' => true,
                    'message' => 'Sub-Category Deleted ',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No Sub-Category Delete',
                ],
                404,
            );
        }
    }
}

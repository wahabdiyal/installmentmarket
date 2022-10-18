<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ctg = Category::whereNull('parent_id')
            ->select('id', 'name')
            ->get();
        if (!$ctg->isEmpty()) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Categories list',
                    'data' => $ctg,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No Category found',
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
        ]);

        return response()->json(
            [
                'status' => true,
                'message' => 'Category Created Successfully',
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
        $ctg = Category::whereNull('parent_id')
            ->select('id', 'name')
            ->where('id', $id)
            ->first();
        if ($ctg) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Category Found',
                    'data' => $ctg,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No Category found',
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
        $category = Category::find($id);
        if ($category) {
            $category->update([
                'name' => $request->name,
            ]);
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Category Update Successfully',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Category Not Found',
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
        $ctg = Category::where('id', $id)->first();
        if (!$ctg) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No Category found',
                ],
                404,
            );
        }

        $sub = Category::where('parent_id', $id);

        $ctg_del = $ctg->delete();

        if ($ctg_del) {
            $sub_ctg_del = $sub->delete();
            if ($sub_ctg_del) {
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Category and SubCategory Delete as well',
                    ],
                    200,
                );
            }
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Category Delete only',
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Not Category Delete',
                ],
                404,
            );
        }
    }
}

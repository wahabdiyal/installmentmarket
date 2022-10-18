<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Image;
use App\Models\Price;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product = Product::with(['images', 'price'])->get();

        if (!$product->isEmpty()) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Product list',
                    'data' => $product,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No Product found',
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
        $product = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'company_id' => 'required|exists:companies,id',
            'condition' => 'required',
            'sub_category_id' => 'required|exists:categories,id',
            'down_pay' => 'required',
            'per_month' => 'required',
            'total_installment' => 'required',
            'images' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($product->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $product->errors(),
                ],
                401,
            );
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'company_id' => $request->company_id,
            'condition' => $request->condition,
            'category_id' => $request->sub_category_id,
        ]);

        $product->price()->create([
            'down_pay' => $request->down_pay,
            'per_month' => $request->per_month,
            'total_installment' => $request->total_installment,
        ]);
        if ($product) {
            foreach ($request->file('images') as $a) {
                $fileName = str_replace(' ', '', $a->getClientOriginalName());
                $a->move(public_path('uploads'), $fileName);
                $product->images()->create([
                    'path' => 'uploads/' . $fileName,
                ]);
            }
        }

        return response()->json(
            [
                'status' => true,
                'message' => 'Product Created Successfully',
                'data' => $product,
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
        $product = Product::with(['price', 'images'])
            ->where('id', $id)
            ->first();
        if ($product) {
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Product Found',
                    'data' => $product,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No Product found',
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
        $product = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'company_id' => 'required|exists:companies,id',
            'condition' => 'required',
            'sub_category_id' => 'required|exists:categories,id',
            'down_pay' => 'required',
            'per_month' => 'required',
            'total_installment' => 'required',
        ]);

        if ($product->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $product->errors(),
                ],
                401,
            );
        }

        $product = Product::find($id);
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'company_id' => $request->company_id,
            'condition' => $request->condition,
            'category_id' => $request->sub_category_id,
        ]);

        $product->price()->update([
            'down_pay' => $request->down_pay,
            'per_month' => $request->per_month,
            'total_installment' => $request->total_installment,
        ]);
        if ($product && $request->file('images')) {
            $imgs = $product->images()->get();
            for ($i = 0; $i < count($imgs); $i++) {
                if (File::exists(public_path($imgs[$i]->path))) {
                    File::delete(public_path($imgs[$i]->path));
                }
            }
            if (count($imgs)) {
                $product->images()->delete();
            }

            foreach ($request->file('images') as $a) {
                $fileName = str_replace(' ', '', $a->getClientOriginalName());
                $a->move(public_path('uploads'), $fileName);
                $product->images()->create([
                    'path' => 'uploads/' . $fileName,
                ]);
            }
        }

        return response()->json(
            [
                'status' => true,
                'message' => 'Product Updated Successfully',
            ],
            200,
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->price()->delete();
            $imgs = $product->images()->get();
           if (count($imgs)) {
            for ($i = 0; $i < count($imgs); $i++) {
                if (File::exists(public_path($imgs[$i]->path))) {
                    File::delete(public_path($imgs[$i]->path));
                }
            }


                $product->images()->delete();

            }
            $product->delete();
            return response()->json(
                [
                    'status' => true,
                    'message' => 'Product Deleted',

                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No Product found',
                ],
                404,
            );
        }
    }
}

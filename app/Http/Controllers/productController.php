<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class productController extends Controller
{
    public function index() {
        $products = product::orderBy('created_at', 'DESC')->get();

        return view('products.list', [
            'products'=> $products
        ]);
    }

    public function create() {
        return view('products.create');
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return redirect()->route('product.create')->withInput()->withErrors($validator);
        }

        if($request->image != "") {
            $rules['image'] = 'image';
        }

        //insert db

        $product = new product();

        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        
        $product->save();

        if($request->image != "") {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;

            $image->move(public_path('uploads/products'), $imageName);

            $product->image = $imageName;
            $product->save();
        }

        

        return redirect()->route('product.index')->with('success', 'Product addedd successfuly');

    }

    public function edit($id) {
        $product = product::findOrFail($id);
        return view('products.edit', [
            'product' => $product
        ]);
    }

    public function update($id, Request $request) {

        $products = product::findOrFail($id);

        $rules = [
            'name' => 'required|min:5',
            'sku' => 'required|min:3',
            'price' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()){
            return redirect()->route('product.edit', $products->id)->withInput()->withErrors($validator);
        }

        if($request->image != "") {
            $rules['image'] = 'image';
        }

        //insert db

        $product = new product();

        $product->name = $request->name;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->description = $request->description;
        
        $product->save();

        if($request->image != "") {
            File::delete(public_path('uploads/products/'.$product->image));
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time().'.'.$ext;

            $image->move(public_path('uploads/products'), $imageName);

            $product->image = $imageName;
            $product->save();
        }

        

        return redirect()->route('product.index')->with('success', 'Product updated successfuly');
    }

    public function destroy($id) {
        $product = product::findOrFail($id);

        File::delete(public_path('uploads/products/'.$product->image));

        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product deleted successfuly');
    }
}

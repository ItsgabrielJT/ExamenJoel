<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::all();
        return view('products.index', compact('products')); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $product = new Product();
        
        return view('products.create', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if($request->has('image')) {
            $img_path = $request->file('image')->store('medias');
            $data['image'] = $img_path;
        }
        Product::create($data);
        return redirect()->route('product.index')->with(['status'=>'Success', 'color' => 'green', 'message'=>'Product Created Sucessfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        // Cuando tenemos relaciones con tabals teemos que colcar un try para evitar errores
        try {
            $product->delete();
            $result = ['status'=>'Success', 'color' => 'green','message'=>'Product Deleted Sucessfully'];
        } catch(Exception $e) {
            $result = ['status'=>'Success', 'color' => 'red','message'=>'Product cannot be delete'];
        }
        return redirect()->route('product.index')->with($result);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        $results = Product::paginate(10);
        $title   = "All Product";
        return view('pages.products.index',compact('results','title'));
    }

    public function create()
    {
        $result    = "";
        $categorys = Category::all();
        $title     = 'New Product';
        return view('pages.products.create',compact('result','title','categorys'));
    }

    public function store(ProductRequest $request)
    {
        DB::beginTransaction();
        try {
            $hargaJual = preg_replace( '/[^0-9.]/', '', $request->price );
            $stockAwal = preg_replace( '/[^0-9.]/', '', $request->stock );

            $product              = new Product();
            $product->category_id = $request->category_id;
            $product->name        = $request->name;
            $product->description = $request->description;
            $product->price       = $hargaJual;
            $product->stock       = $stockAwal;
            $product->status      = $request->status;
            $product->is_favorite = $request->is_favorite;
            $product->save();

            if($request->hasFile('image') ){
                $images    = $request->file('image');
                $namaImage = time().'.'.$images->getClientOriginalExtension();
                $destinationPath = storage_path('/app/public/products');

                !is_dir($destinationPath) && mkdir($destinationPath, 0777, true);

                $imgFile = Image::make($images->getRealPath());
                $imgFile->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$namaImage);

                $product->image = 'storage/products/'.$namaImage;
                $product->save();
            }
            DB::commit();
            return redirect()->route('products.index')->with('success','Product successfull to saved!');
        } catch (\Throwable $th) {
            //dump error variable from $th
            DB::rollBack();
            return redirect()->back()->with('error','Product failed to saved!');
        }
    }

    public function edit($id)
    {
        $result = Product::find($id);
        $title  = 'Edit Product';
        $categorys = Category::all();
        return view('pages.products.create',compact('result','title','categorys'));
    }

    public function update(ProductRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $hargaJual = preg_replace( '/[^0-9.]/', '', $request->price );
            $stockAwal = preg_replace( '/[^0-9.]/', '', $request->stock );

            $product              = Product::find($id);
            $product->category_id = $request->category_id;
            $product->name        = $request->name;
            $product->description = $request->description;
            $product->price       = $hargaJual;
            $product->stock       = $stockAwal;
            $product->status      = $request->status;
            $product->is_favorite = $request->is_favorite;
            $product->save();

            if($request->hasFile('image') ){
                $images          = $request->file('image');
                $namaImage       = time().'.'.$images->getClientOriginalExtension();
                $destinationPath = storage_path('/app/public/products');
                $imgFile         = Image::make($images->getRealPath());

                $imgFile->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$namaImage);

                $product->image = 'storage/products/'.$namaImage;
                $product->save();
            }
            DB::commit();
            return redirect()->route('products.index')->with('success','Product successfull to updated!');
        } catch (\Throwable $th) {
            //dump error variable from $th
            DB::rollBack();
            return redirect()->back()->with('error','Product failed to updated!');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $product = Product::find($id);
            if(File::exists(($product['image']))) {
                File::delete(($product['image']));
            }
            $product->delete();
            DB::commit();
            return redirect()->route('products.index')->with('success','Product successfull to deleted!');
        } catch (\Throwable $th) {
            //dump error variable from $th
            DB::rollBack();
            return redirect()->back()->with('error','Product failed to deleted!');
        }
    }
}

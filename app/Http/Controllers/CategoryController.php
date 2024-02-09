<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $results = Category::paginate(10);
        $title   = "All Category";
        return view('pages.categorys.index',compact('results','title'));
    }

    public function create()
    {
        $result = "";
        $title  = 'New Category';
        return view('pages.categorys.create',compact('result','title'));
    }

    public function store(CategoryRequest $request)
    {
        DB::beginTransaction();
        try {
            if($request->hasFile('image') ){
                $images    = $request->file('image');
                $namaImage = time().'.'.$images->getClientOriginalExtension();
                $destinationPath = storage_path('/app/public/categorys');

                !is_dir($destinationPath) && mkdir($destinationPath, 0777, true);

                $imgFile = Image::make($images->getRealPath());
                $imgFile->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$namaImage);

                $data = [
                    'name'        => $request->name,
                    'description' => $request->description,
                    'image'       => 'storage/categorys/'.$namaImage,
                ];
                Category::create($data);
            }
            DB::commit();
            return redirect()->route('categorys.index')->with('success','Category successfull to saved!');
        } catch (\Throwable $th) {
            //dump error variable from $th
            DB::rollBack();
            return redirect()->back()->with('error','Category failed to saved!');
        }
    }

    public function edit($id)
    {
        $result = Category::find($id);
        $title  = 'Edit Category';
        return view('pages.categorys.create',compact('result','title'));
    }

    public function update(CategoryRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            if($request->hasFile('image') ){
                $images          = $request->file('image');
                $namaImage       = time().'.'.$images->getClientOriginalExtension();
                $destinationPath = storage_path('/app/public/categorys');
                $imgFile         = Image::make($images->getRealPath());

                $imgFile->resize(200, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath.'/'.$namaImage);

                $category = Category::find($id);
                $data = [
                    'name'        => $request->name,
                    'description' => $request->description,
                    'image'       => 'storage/categorys/'.$namaImage,
                ];
                $category->update($data);
            }
            DB::commit();
            return redirect()->route('categorys.index')->with('success','Category successfull to updated!');
        } catch (\Throwable $th) {
            //dump error variable from $th
            DB::rollBack();
            return redirect()->back()->with('error','Category failed to updated!');
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $category = Category::find($id);
            if(File::exists(storage_path($category['image']))) {
                File::delete(storage_path($category['image']));
            }
            $category->delete();
            DB::commit();
            return redirect()->route('categorys.index')->with('success','Category successfull to deleted!');
        } catch (\Throwable $th) {
            //dump error variable from $th
            DB::rollBack();
            return redirect()->back()->with('error','Category failed to deleted!');
        }
    }
}

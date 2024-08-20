<?php

namespace App\Http\Controllers\API;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Auth;


class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
       $data['posts'] = Post::orderByDesc('id')->get();;
       return $this->sendResponse($data,"Data fetch successfully");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validate = Validator::make(
                $request->all(),
                [
                    'title' => 'required',
                    'description' => 'required',
                    'image' => 'required|mimes:jpg,png,jpeg'
                ]
            );
            if($validate->fails()){
                return $this->sendError('Validation error',$validate->errors());
            }
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . "." . $ext;
            $image->move(public_path().'/uploads',$imageName);
            
            $post = Post::create([
                'title'=>$request->title,
                'user_id'=>Auth::id(),
                'description'=>$request->description,
                'image'=>$imageName
            ]);
            return $this->sendResponse($post,'Post Data Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post'] = Post::findOrFail($id);
        return $this->sendResponse($data,"Single Data Shown");
    }
   

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'mimes:jpg,png,jpeg'
            ]);
        if($validate->fails()){
            return $this->sendError('Validation error',$validate->errors());
        }
        $postImage = Post::select('id','image')->where('id',$id)->first();
        if($request->image != ''){
            $path = public_path(). '/uploads/';
            if($postImage->image != '' && $postImage->image != null){
                $old_file = $path.$postImage->image;
                //return $old_file;
                if(file_exists($old_file)){
                    unlink($old_file);
                }
            }
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . "." . $ext;
            $image->move(public_path().'/uploads',$imageName);
        }else{
            $imageName = $postImage->image;
        }
        $post = Post::where('id',$id)->update([
            'title'=>$request->title,
            'description' => $request->description,
            'image' => $imageName
        ]);

        return $this->sendResponse($post,'Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagePath = Post::select('image')->where('id',$id)->first();
        $filePath = public_path().'/uploads/'.$imagePath['image'];
        unlink($filePath);
        $post = Post::where('id',$id)->delete();
        return $this->sendResponse($post,'Post Deleted Successfully');
    }
}

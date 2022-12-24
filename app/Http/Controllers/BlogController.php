<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\User;

class BlogController extends Controller
{
    public function store(StoreBlogRequest $request)
    {
        $data = [
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'user_id' => auth()->id()
        ];
        if($request->hasFile('thumbnail'))
        {
            $thumbnail = $request->file('thumbnail');
            $newThumbnailName = time().'.'.$thumbnail->getClientOriginalExtension();
            $path = public_path('/uploads/blogs');
            $thumbnail->move($path, $newThumbnailName);
            $data['thumbnail'] = $newThumbnailName;
        }
        $blog = Blog::create($data);
        return response()->json([
            'error' => 'false',
            'message' => 'Blog created successfully!!',
            'data' => $blog
        ], 201);
    }

    public function update(UpdateBlogRequest $request, $id)
    {
        $blog = Blog::find($id);
        if($blog)
        {
            if($request->title)
            {
                $data['title'] = $request->input('title');
            }
            if($request->description)
            {
                $data['description'] = $request->input('description');
            }
            if($request->hasFile('thumbnail'))
            {
                $oldImage = $blog->thumbnail;
                if($oldImage)
                {
                    unlink('uploads/blogs/'.$oldImage);
                }
                $thumbnail = $request->file('thumbnail');
                $newThumbnailName = time().'.'.$thumbnail->getClientOriginalExtension();
                $path = public_path('/uploads/blogs');
                $thumbnail->move($path, $newThumbnailName);
                $data['thumbnail'] = $newThumbnailName;
            }

            $blog->update($data);
            return response()->json([
                'error' => false,
                'message' => 'Blog updated successfully!!',
                'data' => $blog
            ]);
        }
        else
        {
            return response()->json([
                'error' => true,
                'message' => 'Blog not found'
            ], 404);
        }
    }
}
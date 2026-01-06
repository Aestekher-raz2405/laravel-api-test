<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('user')->paginate(2);
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {

    //    $data = $request->only(['title', 'content']);
       $data = $request->validated();

       $data['user_id'] = 2; // Simulating an authenticated user with ID 2
       // Here you would typically save the post to the database, e.g.:
       $post = Post::create($data);

       return response()->json(new PostResource($post), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post  $post)
    {
        return response()->json(new PostResource($post))
            // ->header('test', 'Raz')
            // ->header('test2', 'Hello')
            ->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->validate([
            'title' => 'required|string|min:2',
            'content' => 'required|string',
        ]);
        return response()->json([
            'message' => 'Post updated successfully',
            'data' => [
                'id' => $id,
                'title' => $data['title'],
                'content' => $data['content'],
            ],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return response()->noContent();
    }
}

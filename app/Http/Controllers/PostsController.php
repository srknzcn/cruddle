<?php

namespace App\Http\Controllers;

use App\Models\Posts;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class PostsController extends Controller
{

    public function index(): Collection|array
    {
        return Posts::all();
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email|unique:posts",
            "post" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }

        $post = new Posts();
        $post->created_at = date("Y-m-d H:i:s");
        $post->email = $request->email;
        $post->post = $request->post;
        $post->save();

        if (!$post->save()) {
            return response()->json([
                "success" => false
            ]);
        }
        return response()->json([
            "success" => true,
            "data" => $post->toArray()
        ]);
    }

    public function show(Request $request, string $id)
    {
        return response()->json(
            Posts::find($id) ?? ["success" => false]
        );
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email",
            "post" => "required"
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }

        return response()->json(
            Posts::where('id', $id)->update($request->toArray()) ? [
                "success" => true
            ] : ["success" => false]
        );
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        return response()->json(
            Posts::where('id', $id)->delete($request->toArray()) ? [
                "success" => true
            ] : ["success" => false]
        );
    }

}

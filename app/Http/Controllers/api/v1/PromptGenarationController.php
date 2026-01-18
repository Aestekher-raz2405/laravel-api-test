<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\GeneratePromptRequest;
use App\Http\Resources\PromptGenerationResource;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromptGenarationController extends Controller
{
   public function __construct(private GeminiService $geminiService)
   {

   }

   /**
    * List Image Generation Prompts
    *
    * Retrieves a paginated list of image generation prompts for the authenticated user.
    * Returns the 10 most recent generations sorted by creation date.
    *
    * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    *
    * @response 200 {"data": [{"id": 1, "image_path": "uploads/images/example.jpg", "generated_prompt": "A beautiful landscape", "created_at": "2026-01-17T10:00:00Z"}], "links": {}, "meta": {}}
    * @unauthenticated
    */
   public function index(Request $request)
   {
     $user = request()->user();
     $query = $user->imageGeneration();
     // Apply search filter
     if(request()->has('search') && !empty(request()->get('search')))
     {
        $search = request()->get('search');
        $query->where('generated_prompt', 'like', '%'.$search.'%');

     }

     //Applying sort
     $allowedSortFields =['created_at','generated_prompt','orginal_filename','file_size'];
     $sortField =['created_at'];
     $sortDirection ='desc';

     if($request->has('sort') && !empty($request->sort))
        {
            $sort = $request->sort;
            if(str_starts_with($sort,'-')){
                $sortField = substr($sort,1);
                $sortDirection ='desc';

            }
            else{
                $sortField = $sort;
                $sortDirection ='asc';
            }
            if(!in_array($sortField,$allowedSortFields)){
                $sortField ='created_at';
                $sortDirection ='desc';
            }
            $query->orderBy($sortField,$sortDirection);
        }


     $imageGenerations = $query->paginate(request()->get('per_page', 10));
     return PromptGenerationResource::collection($imageGenerations);

   }
    /**
     * Generate Image Prompt
     *
     * Analyzes an uploaded image using Google Gemini AI and generates a descriptive prompt.
     * The image is stored securely and the generated prompt is saved to the database.
     * Accepts JPEG, PNG, and other common image formats.
     *
     * @param  \App\Http\Requests\GeneratePromptRequest  $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @bodyParam image file required The image file to analyze (max 10MB). Example: image.jpg
     *
     * @response 201 {"data": {"id": 1, "image_path": "uploads/images/example_abc123.jpg", "generated_prompt": "A serene mountain landscape at sunset", "file_size": 2048576, "original_filename": "mountain.jpg", "mime_type": "image/jpeg", "created_at": "2026-01-17T10:30:00Z"}}
     * @response 422 {"message": "The image field is required.", "errors": {"image": ["The image field is required."]}}
     * @response 500 {"message": "Failed to generate prompt", "error": "Service unavailable"}
     *
     * @unauthenticated
     */

   public function store(GeneratePromptRequest $request)
   {
        $data = $request->validated();
        $user = $request->user();
        $image = $request->file('image');
        $originalName = $image->getClientOriginalName();
        $senitizedName = preg_replace('/[^a-zA-Z0-9._-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
        $extension = $image->getClientOriginalExtension();
        $safeFileName = $senitizedName . '_' . Str::random(10) . '.' . $extension;
        $imagePath =$image->storeAs('uploads/images', $safeFileName,'public');

        $generatedPrompt = $this->geminiService->generatedImagePrompt($image);
        $promptGeneration = $user->promptGeneration()->create([
            'image_path' => $imagePath,
            'generated_prompt' => $generatedPrompt,
            'file_size' => $image->getSize(),
            'orginal_filename' => $originalName,
            'mime_type' => $image->getMimeType(),
        ]);
        return response()->json(new PromptGenerationResource($promptGeneration), 201);

   }
}

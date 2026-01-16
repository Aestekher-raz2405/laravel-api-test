<?php
namespace App\Services;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Gemini\Data\Blob;
use Gemini\Enums\MimeType;


class GeminiService
{
    public function test()
    {
        try {
            // We use the 'gemini-1.5-flash' model for fast, successful testing
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->generateContent('Return the word "Success" and nothing else.');

            return response()->json([
                'status' => 'Connected!',
                'gemini_says' => $result->text(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function generatedImagePrompt(UploadedFile $image)
    {
        $image_data = base64_encode(file_get_contents($image->getRealPath()));
        $mime_type = $image->getMimeType();

        $prompt = 'Analyze this image and generate a detailed, descriptive prompt that could be used to recrelte a similar image with AI image generation tools.
        The prompt should be comprehensive, describing the visual elements, style, composition, lighting, colors, and any other relevant details.
        Make it detailed enough that someone could use it to generate a similar image.
        You must preserve aspect ratio exact as the original image has or very close to it. No extra explanations, just provide the prompt.
        ';

        $generated_prompt = Gemini::generativeModel(model: 'gemini-2.5-flash')
            ->generateContent([
                $prompt,
                new Blob(
                    mimeType: MimeType::from($mime_type),
                    data: $image_data
                )
            ]);

        return trim($generated_prompt->text());
    }


}

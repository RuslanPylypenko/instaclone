<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'text' => 'required|string|max:254',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hashtags' => 'array',
            'hashtags.*' => 'string|max:255',
            'likes' => 'integer',
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Text is required',
            'text.string' => 'Text must be a string',
            'text.max' => 'Text must not be greater than 254 characters',
            'images.array' => 'Images must be an array',
            'images.*.image' => 'Images must be an image',
            'images.*.mimes' => 'Images must be of type jpeg, png, jpg, gif, svg',
            'images.*.max' => 'Images must not be greater than 2048',
            'hashtags.array' => 'Hashtags must be an array',
            'hashtags.*.string' => 'Hashtags must be a string',
            'hashtags.*.max' => 'Hashtags must not be greater than 255 characters',
            'author_id.required' => 'Author ID is required',
            'author_id.integer' => 'Author ID must be an integer',
            'author_id.exists' => 'Author ID does not exist',
            'likes.integer' => 'Likes must be an integer',
        ];
    }
}

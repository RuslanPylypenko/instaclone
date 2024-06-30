<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->id === $this->post->author_id;
    }

    public function rules(): array
    {
        return [
            'text' => 'sometimes|string|max:254',
            'images' => 'sometimes|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'hashtags' => 'sometimes|array',
            'hashtags.*' => 'string|max:255',
            'author_id' => 'sometimes|integer|exists:users,id',
            'likes' => 'sometimes|integer',
            'token' => 'required|string|exists:posts,token',
        ];
    }

    public function messages(): array
    {
        return [
            'text.required' => 'Text is required',
            'text.string' => 'Text must be a string',
            'text.max' => 'Text must not be greater than 254 characters',
            'images.array' => 'Images must be an array',
            'images.*.image' => 'Images must be an image file',
            'images.*.mimes' => 'Images must be of type jpeg, png, jpg, gif, svg',
            'images.*.max' => 'Images must not be greater than 2048 kilobytes',
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

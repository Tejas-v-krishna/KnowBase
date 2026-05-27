<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuestionRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array {
        return [
            'title' => 'required|string|max:255',
            'topic_id' => 'required|exists:topics,id',
            'school_level' => 'required|in:elementary,middle_school,high_school,college',
            'body' => 'required|string',
            'tags' => 'nullable|string',
        ];
    }
}



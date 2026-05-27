<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreThreadRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array {
        return [
            'title' => 'required|string|max:255',
            'topic_id' => 'nullable|exists:topics,id',
            'body' => 'required|string',
            'tags' => 'nullable|string',
        ];
    }
}

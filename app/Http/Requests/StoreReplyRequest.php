<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReplyRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array {
        return [
            'body' => 'required|string',
            'parent_id' => 'nullable|exists:replies,id',
        ];
    }
}

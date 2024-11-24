<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store()
    {
        return [
            'title'             => 'required|max:255',
            'description'       => ['required'],
            'status'            => ['required'],
            'assigned_users'    => 'nullable|array',
            'assigned_to'       => 'nullable|integer',
            'start_date'        => 'nullable|date',
            'due_date'          => 'nullable|date',

        ];
    }

    protected function update()
    {
        return [
            'title'             => 'max:255',
            'description'       => ['nullable'],
            'status'            => ['required'],
            'assigned_users'    => 'nullable',
            'assigned_to'       => 'nullable|integer',
            'start_date'        => 'nullable|date',
            'due_date'          => 'nullable|date',


            // Use 'user' instead of 'id' in apiResourceRoute
            // 'email'     => ['required', 'email', Rule::unique('users')->ignore($this->route('user'))],
            // 'phone'     => ['required', Rule::unique('users')->ignore($this->route('user'))],
        ];
    }
}

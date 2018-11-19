<?php
namespace Piclou\Ikcms\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'firstname.required' => "Le prénom est obligatoire.",
            'lastname.required' => "Le nom est obligatoire.",
            'email.required' => "L'email est obligatoire.",
            'email.email' => "L'email n'est pas au bon format.",
            'role.required' => "Le rôle est obligatoire.",
        ];
    }
}
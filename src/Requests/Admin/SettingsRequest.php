<?php
namespace Piclou\Ikcms\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
            'website_name' => 'required',
            'website_email' => 'required|email',
            'website_logo' => 'image'
        ];
    }

    public function messages()
    {
        return [
            'website_name.required' => "Le nom du site Internet est obligatoire.",
            'website_email.required' => "L'email pour les formulaire est obligatoire.",
            'website_email.email' => "L'email n'est pas au bon format.",
            'website_logo.image' => "Votre logo doit être une image.",
        ];
    }
}
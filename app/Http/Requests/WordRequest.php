<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class WordRequest extends FormRequest
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
            'subject'                     => ['required', 'string'],
            'user_name'                   => ['required', 'string'],
            'email'                       => ['required', 'email', 'unique:ticket,user_email'],
            'ftp_login'                   => ['array'],
            'ftp_login.*'                 => ['required', 'string'],
            'ftp_password'                => ['array'],
            'ftp_password.*'              => ['required'],
            'content.*'                   => ['required'],
            'author'                      => ['array'],
            'author.*'                    => ['required'],

        ];
    }

    public function messages()
    {
        return [
            'subject.required'            => 'Отсутствует параметр subject',
            'subject.string'              => 'Поле "Предмет" должно быть строкой.',
            'user_name.required'          => 'Поле "Имя пользователя" обязательно для заполнения.',
            'user_name.string'            => 'Поле "Имя пользователя" должно быть строкой.',
            'email.required'              => 'Поле "Email" обязательно для заполнения.',
            'email.email'                 => 'Поле "Email" должно содержать @.',
            'email.unique'                => 'Поле "Email" должно содержать уникальным.',
            'ftp_login.array'             => 'Поле "Ftp_login" должно быть массивом.',
            'ftp_login.*.required'        => 'Поле "Ftp_login" обязательно для заполнения.',
            'ftp_login.string.*'          => 'Поле "Ftp_login" должно быть строкой.',
            'ftp_password.array'          => 'Поле "Ftp_password" должно быть строкой.',
            'ftp_password.*.required'     => 'Поле "Ftp_password" обязательно для заполнения.',
            'content.*.required'          => 'Поле "Content" обязательно для заполнения.',
            'content.array'               => 'Поле "Content" должно быть массивом.',
            'author.*.required'           => 'Поле "Author" обязательно для заполнения.',
            'author.array'                => 'Поле "Author" должно быть массивом.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(response()->json($errors, JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }
}

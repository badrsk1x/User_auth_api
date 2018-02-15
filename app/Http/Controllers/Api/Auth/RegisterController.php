<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Routing\Helpers;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegisterController extends Controller
{

    use RegistersUsers;
    use Helpers;

    public function register(Request $request){

        // Валидация Данных
        $validator = $this->validator($request->all());
        if($validator->fails()){
            // ошибка
            throw new StoreResourceFailedException("Сорри мистер, неправильные данные!", $validator->errors());
        }
        // Создаем пользователя
        $user = $this->create($request->all());

        if($user){
            // генерируем токен
            $token = JWTAuth::fromUser($user);
            // ответ
            return $this->response->array([
                "token" => $token,
                "message" => "Пользователь успешно создан!",
                "status_code" => 201
            ]);
        } else{
            // если пошло что то не так
            return $this->response->error("Пользователь не существует...", 404);
        }
    }


    /**
     * Валидаторы
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
    }

    /**
     * Создание пользователя
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}

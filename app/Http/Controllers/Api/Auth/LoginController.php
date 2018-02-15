<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Dingo\Api\Routing\Helpers;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\User;


class LoginController extends Controller
{

    use AuthenticatesUsers;
    use helpers;

    /**
     * Метод авторизации
     */

    public function login(Request $request){

        // поиск пользователя по email или имя
        $user = User::where('email', $request->email)->orWhere('name', $request->name)->first();

        // если нашли, дальше проверяем пароль
        if($user && Hash::check($request->get('password'), $user->password)){
            // если все ок, генерируем токен и отправляем ответ
            $token = JWTAuth::fromUser($user);
            return $this->sendLoginResponse($request, $token);
        }
           // а вот если нет, отправляем тоже ответ , ну с ошибки
        return $this->sendFailedLoginResponse($request);
    }

    public function sendLoginResponse(Request $request, $token){

        // Удаляем блокировки входа для учетных данных пользователя.
        $this->clearLoginAttempts($request);
        return $this->authenticated($token);
    }

    public function authenticated($token){
        // Вовращаем ответ
        return $this->response->array([
            'token' => $token,
            'status_code' => 200,
            'message' => 'Пользователь авторизован'
        ]);
    }

    public function sendFailedLoginResponse(){
        throw new UnauthorizedHttpException("Bad Credentials");
    }

    public function logout(){
        $this->guard()->logout();
    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Log;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Mockery\Exception;

class AuthController extends Controller
{
    // Метод для регистрации нового пользователя
    public function register (Request $rq)
    {
        // Проверяем наличие всех параметров для записи в базу данных.
        // Иначе возвращаем пустой результат.

        if ($rq->name != null && $rq->email != null && $rq->password != null){

            // Проверяем уникальность пользователя
            $userIsset = User::where("email",$rq->email)->get()->count();

            // Если пользователь не найден добавляем нового пользователя
            if ($userIsset){
                return "user isset";
            }
            else{
                $newUser = User::create([
                    'name' => $rq->name,
                    'email' => $rq->email,
                    'password' => Hash::make($rq->password),
                    'token'     => md5(uniqid(rand(), true))
                ]);

                Log::create([
                    "user_id"   => $newUser->id,
                    "event"     => "Register user",
                    "result"    => "ok"
                ]);

                Auth::login($newUser);

                return "ok";
            }



        }
        else{
            return "empty";
        }



    }

    public function login (Request $request)
    {
        if ($request->email !== null && $request->password !== null){

            $user = User::where("email",$request->email)->first();

            if(Hash::check($request->password,$user->password)){
                Auth::login($user);

                Log::create([
                    "user_id"   => $user->id,
                    "event"     => "Login user",
                    "result"    => "ok"
                ]);

                return "match";
            }
            else{
                return "not match";
            }

        }
        else{
            return "empty";
        }
    }


    // Метод для утентификации пользователя

    public function isAuth (Request $request,$stat = null)
    {
        // Проверяем авторизирован ли пользователь

        if ($stat == null){

            if (Auth::check()){
                return "ok";
            }
            else{
                return "not";
            }

        }
        elseif ($stat == "user"){
            // Запрашиваем модель пользователя
            return Auth::user();
        }
        elseif ($stat == "conts"){
            // Запрашиваем контакты пользователя

            if (Auth::check()){

                try {
                    $conts = Contact::where("user_id",Auth::user()->id)->get();
                }catch (\Exception $e){
                    $conts = "empty";
                }

                return [Auth::user(),$conts];

            }

        }
        elseif ($stat == "conts"){
            // Запрашиваем контакты пользователя

            if (Auth::check()){

                try {
                    $logs = Contact::where("user_id",Auth::user()->id)->get();
                }catch (\Exception $e){
                    $logs = "empty";
                }

                return [Auth::user(),$logs];

            }

        }
        elseif ($stat == "logout"){
            // Разлогиниваем текущего авторизированного пользователя
            Auth::logout();
            return "Logout";
        }

    }



}

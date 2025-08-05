<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class SessionTokenCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

     private function base64DecodeWithSecret($encodedData, $secretKey) {

        $decodedData = base64_decode($encodedData);
        $originalData = substr($decodedData, 0, -strlen($secretKey));
        
        return json_decode($originalData, true);
    }


    public function handle($request, Closure $next)
    {

        if(config('static.app_env') == 'local')
        {

            if (!Session::has('user_module') || !Session::has('modules')) {

                $parameters = $request->all();
        
                if (!isset($parameters['user']) || !isset($parameters['module']) || !isset($parameters['key_module'])) {
                    echo "<script>alert('Invalid request parameters');</script>";
                    echo "<script>setTimeout(function() { window.location.href = '/'; }, 1000);</script>";
                    exit;
                }

                $secretKey = config('static.key_static');
                $user = $this->base64DecodeWithSecret($parameters['user'], $secretKey);
                $module = $this->base64DecodeWithSecret($parameters['module'], $secretKey);
                $key_module = $this->base64DecodeWithSecret($parameters['key_module'], $secretKey);
        

                $secretKeyModule = config('static.key_module');

                if ($key_module != $secretKeyModule) {
                    echo "<script>alert('Key module tidak valid');</script>";
                    echo "<script>setTimeout(function() { window.location.href = '/'; }, 1000);</script>";
                    exit;
                }
        
  
                Session::put('user_module', $user);
                Session::put('modules', $module);
                Session::put('user', $user);
            }
            return $next($request);
        }
        $url_lobby = config('static.url_portal_ts3_main');

     
        if(Session::has('user_module') && Session::has('modules'))
        {
            $user = Session::get('user_module');

            $body = [
                'session_token' => $user['session_token'],
                'username'      => $user['username']
            ];
            $timestamp = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
            $encryptionKey = config('static.key_access') . $timestamp;
            $keyPun = hash(config('static.key_hash'), $encryptionKey);
    
            $responseSession = Http::withHeaders([
                'Content-Type' => 'application/json',
                'key-service' => $keyPun,
                'timestamp' => $timestamp
            ])->withoutVerifying()->post(config('static.url_access_session'), $body);
    
            $responseSessionData = json_decode($responseSession ,true);

           
                
            if (isset($responseSessionData['success']) && $responseSessionData['success'] === false) {
                echo "<script>alert('Session anda tidak aktif');</script>";
                echo "<script>setTimeout(function() { window.location.href = '$url_lobby'; }, 1000);</script>";
                exit;
            }
            else
            {
                return $next($request);
            }

        }

        Log::info('Session before: ', Session::all());

        $parameters = $request->all();
        
       
        if (!isset($parameters['user']) || !isset($parameters['module']) || !isset($parameters['key_module'])) {
            echo "<script>alert('Invalid request parameters');</script>";
            echo "<script>setTimeout(function() { window.location.href = '$url_lobby'; }, 1000);</script>";
            exit;
        }
        $secretKey = config('static.key_static');

        $user = $this->base64DecodeWithSecret($parameters['user'], $secretKey);
        $module = $this->base64DecodeWithSecret($parameters['module'], $secretKey);
        $key_module = $this->base64DecodeWithSecret($parameters['key_module'], $secretKey);

       
        $secretKeyModule =  config('static.key_module');
        // dd($user,$module ,$key_module,$secretKeyModule);
        if ($key_module != $secretKeyModule) {
            echo "<script>alert('Key module tidak valid');</script>";
            echo "<script>setTimeout(function() { window.location.href = '$url_lobby'; }, 1000);</script>";
            exit;
        }

        // Begin check session active
        $body = [
            'session_token' => $user['session_token'],
            'username'      => $user['username']
        ];


        $timestamp = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $encryptionKey = config('static.key_access') . $timestamp;
        $keyPun = hash(config('static.key_hash'), $encryptionKey);
     
        $responseSession = Http::withHeaders([
            'Content-Type' => 'application/json',
            'key-service' => $keyPun,
            'timestamp' => $timestamp
        ])->withoutVerifying()->post(config('static.url_access_session'), $body);

        $responseSessionData = json_decode($responseSession ,true);


        if (isset($responseSessionData['success']) && $responseSessionData['success'] === false) {
            echo "<script>alert('Session anda tidak aktif');</script>";
            echo "<script>setTimeout(function() { window.location.href = '$url_lobby'; }, 1000);</script>";
            exit;
        }
        // End check session active

        // Begin Check Module user
        $body = [
            'module' => $module['module'],
            'username' => $user['username']
        ];
        $timestamp = Carbon::now('Asia/Jakarta')->format('Y-m-d H:i:s');
        $Encryp  = config('static.key_access') . $timestamp;
        $keyPun = hash(config('static.key_hash'), $Encryp);

        $responseModule = Http::withHeaders([
            'Content-Type' => 'application/json',
            'key-service' => $keyPun,
            'timestamp' => $timestamp
        ])->withoutVerifying()->post(config('static.url_access_module_user'), $body);

        $responseModuleData = json_decode($responseModule ,true); 

        if (isset($responseModuleData['success']) && $responseModuleData['success'] === false) {
            echo "<script>alert('Anda tidak memiliki akses module');</script>";
            echo "<script>setTimeout(function() { window.location.href = '$url_lobby'; }, 1000);</script>";
            exit;
        }
        // End Check module user

        // Put Session
        Session::put('user_module', $user);
        Session::put('modules', $module);


        //extended for module
        Session::put('user', $user);

        Log::info('Session after: ', Session::all());

        return $next($request);
 
    }

    

}
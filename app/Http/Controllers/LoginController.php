<?php
  // 暗黙の型変換をしない（厳格化する）
  declare(strict_types=1);

  namespace App\Http\Controllers;

  use App\Providers\RouteServiceProvider;
  use Illuminate\Http\Request;
  use Illuminate\Support\Facades\Auth;

  class LoginController extends Controller
  {
    public function index()
    {
      return view("auth.login");
    }

    public function authenticate(Request $request)
    {
      $credentials = $request -> only("email", "password");

      if(Auth::attempt($credentials)){
        $request -> session()->regenerate();

        // intendedメソッド・・・認証前のページにリダイレクト
        return redirect()->intended(RouteServiceProvider::HOME);
      }

      return back()->withErrors([
        "message" => "メールアドレスまたはパスワードが正しくありません",
      ]);
    }

    public function logout(Request $request)
    {
      Auth::logout();
      $request -> session() -> invalidate();
      $request -> session() -> regenerateToken();
      return redirect(RouteServiceProvider::HOME);
    }
  }
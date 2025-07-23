<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Login extends Component
{
    public $email;
    public $password;
    public $remember_me = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {

        $this->validate();
        $obj_user = new User();
        $user = $obj_user->getUserByEmail($this->email);
        // dd($this->email, $this->password);
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember_me)) {
            auth()->login($user,$this->remember_me);
            // Nếu đăng nhập thành công, chuyển hướng đến trang dashboard
            return redirect()->intended('/dashboard');
        }else{
            return $this->addError('password', trans('auth.password'));
        }
        
    }
    public function logout()
    {
        Auth::guard('users')->logout();
        return redirect()->intended(route('login'));
    }
    public function render()
    {
        return view('livewire.auth.login');
    }
}

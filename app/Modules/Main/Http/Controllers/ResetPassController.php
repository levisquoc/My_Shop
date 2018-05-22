<?php

namespace App\Modules\Main\Http\Controllers;

use App\Modules\Acl\Models\Admin;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Mail;

class ResetPassController extends Controller
{

    public function postforgotpass(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email'
        ]);

        $user = Admin::whereEmail($request->email)->first();

        if (count($user) == 0) {
            return redirect()->back()->with([
                'error' => 'Your email don\'t exists.'
            ]);
        }

        $token = csrf_token();

        if (DB::table('admin_password_resets')->where('email', $user->email)->first()) {

            $reminder = DB::table('admin_password_resets')->where('email', $user->email)->first();

        } else {

            DB::table('admin_password_resets')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            $reminder = DB::table('admin_password_resets')->where('email', $user->email)->first();

        }

        $this->sendEmail($user, $reminder->token);

        return redirect()->back()->with([
            'status_type' => 'success',
            'status' => 'Reset code was send to your email.'
        ]);
    }

    private function sendEmail($user, $code)
    {
        Mail::send('main::mail.forgot-mail', [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $code
        ], function ($message) use ($user) {
            $message->to($user->email);

            $message->subject('Reset your password');
        });
    }

    public function resetpassword($token, Request $request)
    {

        $email = $request->email;

        $check = DB::table('admin_password_resets')->where('email', $email)->where('token', $token)->where('created_at',
            '>', Carbon::now()->subHours(2))->first();

        if (isset($check)) {

            return view('main::recovery-password', compact('email', 'token'));

        } else {

            $check = DB::table('admin_password_resets')->where('email', $email)->where('token', $token)->delete();

            return redirect()->route('admin.login');

        }

    }

    public function postresetpassword(Request $request)
    {

        $this->validate($request, [

            'password' => 'required|min:6|confirmed',
            'secret_password' => 'required|min:6'

        ]);

        $user = Admin::whereEmail($request->email)->first();

        if (Hash::check($request->secret_password, $user->secret_password)) {
            $data['password'] = bcrypt($request->password);
            Admin::where('email', $request->email)->update($data);

            DB::table('admin_password_resets')->where('email', $request->email)->where('token',
                $request->token)->delete();

            return redirect()->route('admin.login')->with([
                'status_type' => 'success',
                'status' => 'Your password has been reset.'
            ]);
        } else {
            $status = ['status_type' => 'error', 'status' => 'Your secret password not correct'];
            return redirect()->back()->with($status);
        }

    }
}

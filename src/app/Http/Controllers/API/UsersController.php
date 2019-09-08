<?php

namespace Kolab\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Support\Facades\Auth;
use Kolab\Http\Controllers\Controller;
use Kolab\User;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'unauthorized'], 401);
        }

        $result = [$user];

        $user->entitlements()->each(
            function ($entitlement) {
                $result[] = User::find($entitlement->user_id);
            }
        );

        return response()->json($result);
    }

    public function refresh()
    {
        if ($token = $this->_guard()->refresh()) {
            return response()
                ->json(['status' => 'successs'], 200)
                ->header('Authorization', $token);
        }

        return response()->json(['error' => 'refresh_token_error'], 401);
    }

    public function register(Request $request)
    {
        $v = Validator::make(
            $request->all(),
            [
                'email' => 'required|email|unique:users',
                'password'  => 'required|min:3|confirmed',
            ]
        );

        if ($v->fails()) {
            return response()->json(['status' => 'error', 'errors' => $v->errors()], 422);
        }

        $user = new User;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json(['status' => 'success'], 200);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->_guard()->attempt($credentials)) {
            return response()->json(['status' => 'success'], 200)->header('Authorization', $token);
        }

        return response()->json(['error' => 'login_error'], 401);
    }

    public function logout()
    {
        $this->_guard()->logout();

        return response()->json(['status' => 'success', 'msg' => 'Logged out Successfully.'], 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        if (!$user) {
            return abort(403);
        }

        $result = false;

        $user->entitlements()->each(
            function ($entitlement) {
                if ($entitlement->user_id == $id) {
                    $result = true;
                }
            }
        );

        if ($user->id == $id) {
            $result = true;
        }

        if (!$result) {
            return abort(404);
        }

        return User::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private function _guard()
    {
        return Auth::guard();
    }
}

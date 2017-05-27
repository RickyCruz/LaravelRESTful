<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Notifications\UserCreated;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Notification;

class UserController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')
            ->only(['store', 'resend']);

        $this->middleware('auth:api')
            ->except(['store', 'verify', 'resend']);

        $this->middleware('transform.input:' . UserTransformer::class)
            ->only(['store', 'update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'verified' =>  User::NOT_VERIFIED,
            'verification_token' => User::generateVerificationToken(),
            'admin' => User::NOT_ADMIN,
        ]);

        return $this->showOne($user, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::IS_ADMIN . ',' . User::NOT_ADMIN,
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && ($user->email != $request->email)) {
            $user->verified = User::NOT_VERIFIED;
            $user->verification_token = User::generateVerificationToken();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (! $user->isVerified()) {
                return $this->errorResponse(
                    'Only verified users can change the user type.',
                    409
                );
            }
            $user->admin = $request->admin;
        }

        if (! $user->isDirty()) {
            return $this->errorResponse(
                'At least one different value must be specified to update the data.',
                422
            );
        }
        $user->save();

        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->showOne($user);
    }

    public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();
        $user->verified = User::VERIFIED;
        $user->verification_token = null;
        $user->save();

        return $this->showMessage('The account has been verified.');
    }

    public function resend(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This user has already been verified.', 409);
        }

        // Attempt 5 times while resting 100ms in between attempts...
        retry(5, function () use ($user) {
            Notification::send($user, new UserCreated);
        }, 100);

        return $this->showMessage('The verification email has been sent.');
    }
}

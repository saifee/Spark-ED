<?php

namespace App\Http\Controllers\Amsl;

use App\Http\Requests\AmslUserInfoRequest;
use App\Models\Amsl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserInfoController extends Controller
{
    public function index(Request $request)
    {
        $query = User::select();
        return $this->getListForUI($query, $request);
    }


    public function create()
    {

    }


    public function store(UserInfoRequest $request)
    {
        $request->merge(['password' => bcrypt($request->input('password'))]);
        User::create($request->all());
        return response()->json([
            'type' => 'success',
            'message' => 'User has been created successfully'
        ]);

    }

    public function show(User $employee)
    {
        //
    }

    public function edit(Request $request, $id)
    {
        return User::find($id);
    }

    public function update(UserInfoRequest $request, $id)
    {
        $loggedInUser = User::find(auth()->user()->id);

        if ($loggedInUser->role == 'admin') {
            if (Hash::check($request->input('admin_password'), $loggedInUser->password)) {
                if ($request->input('password')) {
                    $request->merge(['password' => bcrypt($request->input('password'))]);
                }
                $user = User::find($id);
                $user->update($request->all());
                return $this->feedBackMessage('success', 'User has been updated successfully');


            } else {
                return $this->feedBackMessage('error', 'Admin Password did not match');
            }

        } else {
            return $this->feedBackMessage('error', 'You are not admin you know');
        }

    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json([
            'message' => [
                'type' => 'success',
                'message' => 'User been deleted successfully'
            ],
        ]);
    }

    public function feedBackMessage($type, $message)
    {
        return response()->json([
            'type' => $type,
            'message' => $message
        ]);
    }

}

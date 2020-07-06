<?php
namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService {
    private $authenticated_user;

    public function __construct() {
        $this->authenticated_user = Auth::user();
    }

    public function login($email, $password) {
        $success = 0;
        $errors = [];
        $data = [];

        $credentials = [
            'email' => $email,
            'password' => $password
        ];

        $user = $this->get_user_by_email($email);

        if ($user) {
            if ($user->status) {
                if (Hash::check($credentials['password'], $user->password)) {
                    $success = 1;
                    $data = $user;
                } else {
                    $errors = 'Invalid user credentials';
                }
            } else {
                $errors = 'Account is inactive';
            }
        } else {
            $errors = 'No user was found';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function create_admin(
        $first_name,
        $last_name,
        $contact_number,
        $email,
        $password,
        $image
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        DB::beginTransaction();
        try {
            $user = new User;
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->contact_number = $contact_number;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->roles = 'test';
            $user->status = 1;
            $user->created_by = $this->authenticated_user->id;
            $user->api_token = Str::random(60);

            if (! empty($image)) {
                $extension = explode('/', mime_content_type($image))[1];
                $file_name = Str::random(20) . '.' . $extension;

                if (file_exists(public_path('images'))) {
                    file_put_contents(public_path('images') . '/' . $file_name, file_get_contents($image));
                    $user->image = $file_name;
                }
            }

            $user->save();

            $success = 1;
            $data = $user;
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            $errors = $ex->getMessage();
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function get_user($user_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $user = User::find($user_id);

            if (!empty($user)) {
                $success = 1;
                $data = $user;
            } else {
                $errors = 'No user found';
            }
        } catch (\Exception $ex) {
            $errors = 'An error occured';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function get_users() {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $users = User::all();
            $success = 1;
            $data = $users;
        } catch (\Exception $ex) {
            $errors = 'An error occured';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function activate_user($user_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $user = User::find($user_id);

            if (!empty($user)) {
                $user->status = 1;
                $user->updated_by = $this->authenticated_user->id;
                $user->save();
                $success = 1;
                $data = $user;
            } else {
                $errors = 'No user was found';
            }
        } catch (\Exception $ex) {
            $errors = 'An error occured';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function deactivate_user($user_id, $api_token) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $user = User::find($user_id);

            if (! empty($user)) {
                $user->status = 0;
                $user->updated_by = $this->authenticated_user->id;
                $user->save();
                $success = 1;
                $data = $user;
            } else {
                $errors = 'No user was found';
            }
        } catch (\Exception $ex) {
            $errors = 'An error occured';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function edit(
        $user_id,
        $first_name,
        $last_name,
        $contact_number,
        $email,
        $status
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $user = User::find($user_id);

            if (! empty($user)) {
                try {
                    $user->first_name = $first_name;
                    $user->last_name = $last_name;
                    $user->email = $email;
                    $user->contact_number = $contact_number;
                    $user->status = $status;
                    $user->updated_by = $this->authenticated_user->id;
                    $user->save();

                    $success = 1;
                    $data = $user;
                } catch (\Exception $ex) {
                    $errors = 'An error occured';
                }
            } else {
                $errors = 'User does not exist';
            }
        } catch (\Exception $ex) {
            $errors = 'An error occured';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    private function get_user_by_email($email) {
        return DB::table('users')->where('email', $email)->first();
    }
}

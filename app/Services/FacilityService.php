<?php
namespace App\Services;

use App\Facility;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class FacilityService {
    private $authenticated_user;

    public function __construct() {
        $this->authenticated_user = Auth::user();
    }

    public function create_facility(
        $first_name,
        $last_name,
        $contact_number,
        $email,
        $password,
        $user_status,
        $facility_name,
        $address,
        $region,
        $province,
        $longitude,
        $latitude,
        $facility_type,
        $facility_status
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $user = new User();
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->contact_number = $contact_number;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->roles = 'representative';
            $user->status = $user_status;
            $user->created_by = $this->authenticated_user->id;
            $user->api_token = Str::random(60);
            $user->save();

            $facility = new Facility();
            $facility->user_id = $user->id;
            $facility->facility_name = $facility_name;
            $facility->address = $address;
            $facility->region = $region;
            $facility->province = $province;
            $facility->longitude = $longitude;
            $facility->latitude = $latitude;
            $facility->facility_type = $facility_type;
            $facility->status = $facility_status;
            $facility->created_by = $this->authenticated_user->id;
            $facility->save();

            $success = 1;
            $data = [
                'facility' => $facility,
                'user' => $user
            ];
        } catch (\Exception $ex) {
            $errors = 'An error occured';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function activate($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $facility = Facility::find($facility_id);

            if (! empty($facility)) {
                $facility->status = 1;
                $facility->updated_by = $this->authenticated_user->id;
                $facility->save();

                $success = 1;
                $data = $facility;
            } else {
                $errors = 'Facility does not exist';
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

    public function deactivate($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $facility = Facility::find($facility_id);

            if (! empty($facility)) {
                $facility->status = 0;
                $facility->updated_by = $this->authenticated_user->id;
                $facility->save();

                $success = 1;
                $data = $facility;
            } else {
                $errors = 'Facility does not exist';
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

    public function get_facility($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $facility = Facility::where('id', $facility_id)->with('user')->get();

            if (! empty($facility)) {
                $success = 1;
                $data = $facility[0];
            } else {
                $errors = 'Facility does not exist';
            }
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function get_facilities() {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $facilities = Facility::all();
            $success = 1;
            $data = $facilities;
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function get_facility_user($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $facility = Facility::find($facility_id);

            if (! empty($facility)) {
                $user = User::find($facility->user_id);

                if (! empty($user)) {
                    $success = 1;
                    $data = $user;
                } else {
                    $errors = 'Unable to find user assigned to facility';
                }
            } else {
                $errors = 'Facility does not exist';
            }
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function edit(
        $facility_id,
        $facility_name,
        $address,
        $region,
        $province,
        $longitude,
        $latitude,
        $facility_type,
        $status
    ) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $facility = Facility::find($facility_id);

            if (! empty($facility_id)) {
                $facility->facility_name = $facility_name;
                $facility->address = $address;
                $facility->region = $region;
                $facility->province = $province;
                $facility->longitude = $longitude;
                $facility->latitude = $latitude;
                $facility->facility_type = $facility_type;
                $facility->status = $status;
                $facility->updated_by = $this->authenticated_user->id;
                $facility->save();

                $success = 1;
                $data = $facility;
            } else {
                $errors = 'Facility does not exist';
            }
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }
}

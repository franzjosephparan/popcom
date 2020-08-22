<?php
namespace App\Services;

use App\BatchInventory;
use App\Facility;
use App\InventoryLedger;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\FacilityType;

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
            $facility = new Facility();
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

            $user = new User();
            $user->facility_id = $facility->id;
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->contact_number = $contact_number;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->roles = 'representative';
            $user->status = $user_status;
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
            $data = [
                'facility' => $facility,
                'user' => $user
            ];
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
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
            $errors = 'An error occurred';
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
            $errors = 'An error occurred';
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
            $facility = Facility::where('id', $facility_id)->with('users')->with('type')->get();
            $batches = BatchInventory::where('facility_id', $facility_id)->get()->toArray();
            $inventory_count = 0;

            foreach ($batches as $batch) {
                $inventory_count += $batch['quantity'];
            }

            $facility[0]['total_inventory_count'] = $inventory_count;

            $dispenses = InventoryLedger::where('transaction_type', 'dispense')
                ->where('facility_id', $facility_id)
                ->get()
                ->toArray();
            $dispense_count = 0;

            foreach ($dispenses as $dispense) {
                $dispense_count += abs($dispense['quantity']);
            }

            $facility[0]['total_dispense_count'] = $dispense_count;

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
            $facilities = Facility::with('type')->get();
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

    public function get_facility_users($facility_id) {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $facility = Facility::find($facility_id);

            if (! empty($facility)) {
                $users = User::where('facility_id', $facility->id)->get();

                $success = 1;
                $data = $users;
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

    public function add_facility_user(
        $facility_id,
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

        try {
            $user = new User();
            $user->facility_id = $facility_id;
            $user->first_name = $first_name;
            $user->last_name = $last_name;
            $user->contact_number = $contact_number;
            $user->email = $email;
            $user->password = Hash::make($password);
            $user->roles = 'representative';
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
            $data = [
                'user' => $user
            ];
        } catch (\Exception $ex) {
            $errors = 'An error occurred';
        }

        return [
            'success' => $success,
            'errors' => $errors,
            'data' => $data
        ];
    }

    public function get_facility_types() {
        $success = 0;
        $errors = [];
        $data = [];

        try {
            $data = FacilityType::all();
            $success = 1;
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

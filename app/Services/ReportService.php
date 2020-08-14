<?php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;
use App\Facility;
use App\BatchInventory;

class ReportService {
    private $authenticated_user;
    protected $current_date;
    protected $current_year;
    protected $start_month;
    protected $start_quarter;
    protected $end_quarter;
    protected $facility;
    protected $months = [];

    public function __construct() {
        $this->authenticated_user = Auth::user();
    }

    public function generate_report($facility_id) {
        $this->setDates();
        $this->setFacility($facility_id);
        $facility_batch = BatchInventory::where('facility_id', $facility_id)->where('status', 1)->with('item')->with('ledger')->get()->toArray();

        $coc_pills_data = $this->getData($facility_batch, 'progestin only pills');
        $pop_pills_data = $this->getData($facility_batch, 'combined oral contraceptive');
        $dmpa_data = $this->getData($facility_batch, 'dmpa');
        $iud_data = $this->getData($facility_batch, 'intrauterine device');
        $implant_data = $this->getData($facility_batch, 'porgestin sub-dermal implant');
        $male_condom_data = $this->getData($facility_batch, 'male condom');
        $female_condom_data = $this->getData($facility_batch, 'female condom');

        print_r($male_condom_data);
        exit;

        $spreadsheet = IOFactory::load(public_path('assets/report.xlsx'));
        $writer = new Xlsx($spreadsheet);

        // set header values
        $spreadsheet->getActiveSheet()->setCellValue('A4', 'NAME OF FACILITY (RHU/MHC/CHO): ' . strtoupper($this->facility->facility_name));
        $spreadsheet->getActiveSheet()->setCellValue('J4', 'NAME OF PROVINCE: ' . strtoupper($this->facility->province));
        $spreadsheet->getActiveSheet()->setCellValue('D5', strtoupper($this->start_month) . '-' . strtoupper($this->current_date));
        $spreadsheet->getActiveSheet()->setCellValue('L5', $this->numberToRomanRepresentation($this->facility->region));
        $spreadsheet->getActiveSheet()->setCellValue('D6', $this->current_year);
        // set table months
        $spreadsheet->getActiveSheet()->setCellValue('H10', strtoupper($this->months[0]->format('F')));
        $spreadsheet->getActiveSheet()->setCellValue('I10', strtoupper($this->months[1]->format('F')));
        $spreadsheet->getActiveSheet()->setCellValue('J10', strtoupper($this->months[2]->format('F')));
        // set coc pills
        $spreadsheet->getActiveSheet()->setCellValue('B11', $coc_pills_data['starting']);
        $spreadsheet->getActiveSheet()->setCellValue('C11', 0);
        $spreadsheet->getActiveSheet()->setCellValue('D11', $coc_pills_data['received']);
        $spreadsheet->getActiveSheet()->setCellValue('E11', $coc_pills_data['additions']);
        $spreadsheet->getActiveSheet()->setCellValue('F11', $coc_pills_data['subtractions']);
        $spreadsheet->getActiveSheet()->setCellValue('H11', $coc_pills_data['month1_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('I11', $coc_pills_data['month2_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('J11', $coc_pills_data['month3_issued']);
        // set pop pills
        $spreadsheet->getActiveSheet()->setCellValue('B12', $pop_pills_data['starting']);
        $spreadsheet->getActiveSheet()->setCellValue('C12', 0);
        $spreadsheet->getActiveSheet()->setCellValue('D12', $pop_pills_data['received']);
        $spreadsheet->getActiveSheet()->setCellValue('E12', $pop_pills_data['additions']);
        $spreadsheet->getActiveSheet()->setCellValue('F12', $pop_pills_data['subtractions']);
        $spreadsheet->getActiveSheet()->setCellValue('H12', $pop_pills_data['month1_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('I12', $pop_pills_data['month2_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('J12', $pop_pills_data['month3_issued']);
        // set dmpa
        $spreadsheet->getActiveSheet()->setCellValue('B13', $dmpa_data['starting']);
        $spreadsheet->getActiveSheet()->setCellValue('C13', 0);
        $spreadsheet->getActiveSheet()->setCellValue('D13', $dmpa_data['received']);
        $spreadsheet->getActiveSheet()->setCellValue('E13', $dmpa_data['additions']);
        $spreadsheet->getActiveSheet()->setCellValue('F13', $dmpa_data['subtractions']);
        $spreadsheet->getActiveSheet()->setCellValue('H13', $dmpa_data['month1_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('I13', $dmpa_data['month2_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('J13', $dmpa_data['month3_issued']);
        // set iud
        $spreadsheet->getActiveSheet()->setCellValue('B14', $iud_data['starting']);
        $spreadsheet->getActiveSheet()->setCellValue('C14', 0);
        $spreadsheet->getActiveSheet()->setCellValue('D14', $iud_data['received']);
        $spreadsheet->getActiveSheet()->setCellValue('E14', $iud_data['additions']);
        $spreadsheet->getActiveSheet()->setCellValue('F14', $iud_data['subtractions']);
        $spreadsheet->getActiveSheet()->setCellValue('H14', $iud_data['month1_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('I14', $iud_data['month2_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('J14', $iud_data['month3_issued']);
        // set implant
        $spreadsheet->getActiveSheet()->setCellValue('B15', $implant_data['starting']);
        $spreadsheet->getActiveSheet()->setCellValue('C15', 0);
        $spreadsheet->getActiveSheet()->setCellValue('D15', $implant_data['received']);
        $spreadsheet->getActiveSheet()->setCellValue('E15', $implant_data['additions']);
        $spreadsheet->getActiveSheet()->setCellValue('F15', $implant_data['subtractions']);
        $spreadsheet->getActiveSheet()->setCellValue('H15', $implant_data['month1_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('I15', $implant_data['month2_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('J15', $implant_data['month3_issued']);
        // set male condom
        $spreadsheet->getActiveSheet()->setCellValue('B16', $male_condom_data['starting']);
        $spreadsheet->getActiveSheet()->setCellValue('C16', 0);
        $spreadsheet->getActiveSheet()->setCellValue('D16', $male_condom_data['received']);
        $spreadsheet->getActiveSheet()->setCellValue('E16', $male_condom_data['additions']);
        $spreadsheet->getActiveSheet()->setCellValue('F16', $male_condom_data['subtractions']);
        $spreadsheet->getActiveSheet()->setCellValue('H16', $male_condom_data['month1_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('I16', $male_condom_data['month2_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('J16', $male_condom_data['month3_issued']);
        // set male condom
        $spreadsheet->getActiveSheet()->setCellValue('B17', $female_condom_data['starting']);
        $spreadsheet->getActiveSheet()->setCellValue('C17', 0);
        $spreadsheet->getActiveSheet()->setCellValue('D17', $female_condom_data['received']);
        $spreadsheet->getActiveSheet()->setCellValue('E17', $female_condom_data['additions']);
        $spreadsheet->getActiveSheet()->setCellValue('F17', $female_condom_data['subtractions']);
        $spreadsheet->getActiveSheet()->setCellValue('H17', $female_condom_data['month1_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('I17', $female_condom_data['month2_issued']);
        $spreadsheet->getActiveSheet()->setCellValue('J17', $female_condom_data['month3_issued']);
        // set last part
        $spreadsheet->getActiveSheet()->setCellValue('A23', ucwords($this->authenticated_user->first_name . ' ' . $this->authenticated_user->last_name));
        $spreadsheet->getActiveSheet()->setCellValue('A25', 'Contact No: ' . $this->authenticated_user->contact_number);
        $spreadsheet->getActiveSheet()->setCellValue('A26', 'Email Address: ' . $this->authenticated_user->email);

        $date = new Carbon();
        $filename = $date->format('m-d-Y') . '-report.xlsx';
        $writer->save($filename);

        return $filename;
    }

    private function getData($facility_batch, $category) {
        // male condom
        $starting = 0;
        $received = 0;
        $additions = 0;
        $subtractions = 0;
        $month1_issued = 0;
        $month2_issued = 0;
        $month3_issued = 0;

        foreach ($facility_batch as $batch) {
            if (! empty($batch['item']['category'])) {
                if ($batch['item']['category'] == $category) {
                    foreach ($batch['ledger'] as $ledger) {
                        $date = new Carbon($ledger['created_at']);
                        $date->setTimezone('Asia/Manila');

                        if (!$date->between($this->start_quarter, $this->end_quarter)) {
                            $starting += $ledger['quantity'];
                        } else {
                            if ($ledger['transaction_type'] == 'starting') {
                                $additions += $ledger['quantity'];
                            }
                            if ($ledger['transaction_type'] == 'receive') {
                                $received += $ledger['quantity'];
                            } else if ($ledger['transaction_type'] == 'adjustment') {
                                if ($ledger['quantity'] > 0) {
                                    $additions += $ledger['quantity'];
                                } else {
                                    $subtractions += $ledger['quantity'];
                                }
                            } else if ($ledger['transaction_type'] == 'dispense') {
                                if ($date->between($this->months[0]->firstOfMonth(), $this->months[0]->endOfMonth())) {
                                    $month1_issued += abs($ledger['quantity']);
                                } else if ($date->between($this->months[3], $this->months[4])) {
                                    $month2_issued += abs($ledger['quantity']);
                                } else if ($date->between($this->months[2]->firstOfMonth(), $this->months[2]->endOfMonth())) {
                                    $month3_issued += abs($ledger['quantity']);
                                }
                            }
                        }
                    }
                }
            }
        }

        return [
            'starting' => $starting,
            'received' => $received,
            'additions' => $additions,
            'subtractions' => $subtractions,
            'month1_issued' => $month1_issued,
            'month2_issued' => $month2_issued,
            'month3_issued' => $month3_issued
        ];
    }

    private function numberToRomanRepresentation($number) {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    private function setDates() {
        $date = new Carbon();
        $date2 = new Carbon();
        $date3 = new Carbon();
        $date4 = new Carbon();
        $date5 = new Carbon();
        $date6 = new Carbon();
        $date7 = new Carbon();

        $this->current_date = $date->format('F d');
        $this->current_year = $date->format('Y');
        $this->start_month = $date->startOfQuarter()->format('F');
        $this->start_quarter = $date->startOfQuarter()->subDay();
        $this->end_quarter = $date2->endOfQuarter();
        $this->months[] = $date3->startOfQuarter();
        $this->months[] = $date4->startOfQuarter()->addMonth();
        $this->months[] = $date5->startOfQuarter()->addMonths(2);
        $this->months[] = $date6->startOfQuarter()->addMonth()->startOfMonth();
        $this->months[] = $date7->startOfQuarter()->addMonth()->endOfMonth();
    }

    private function setFacility($facility_id) {
        $this->facility = Facility::find($facility_id);
    }
}

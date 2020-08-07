<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Controller extends BaseController
{
    public function index() {
        return view('index');
    }

    public function generate_report() {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load(public_path('assets/report.xlsx'));
        print_r($spreadsheet->getActiveSheet()->getCell('A1'));
        exit;
        $writer = new Xlsx($spreadsheet);
        $writer->save('test.xlsx');
    }
}

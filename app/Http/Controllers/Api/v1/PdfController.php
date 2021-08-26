<?php


namespace App\Http\Controllers\Api\v1;

use Illuminate\Support\Facades\App;

class PdfController extends Controller
{
    public function index()
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf = $pdf->loadView('provider.summary');
        return $pdf->stream();
    }

}

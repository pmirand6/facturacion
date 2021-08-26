<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ProviderSummaryFE extends Mailable
{
    use Queueable, SerializesModels;

    public $provider;
    public $arrayDate;

    /**
     * Create a new message instance.
     *
     * @param $provider
     * @param $arrayDate
     */
    public function __construct($provider, $arrayDate)
    {
        $this->provider = $provider;
        $this->arrayDate = $arrayDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        try {

            $pdf = App::make('dompdf.wrapper');
            $pdfContent = $pdf->loadView('provider.summary_fe', [
                'provider' => $this->provider,
                'dateGenerated' => $this->arrayDate['dateGenerated'],
                'dateFrom' => $this->arrayDate['dateFrom'],
                'dateTo' => $this->arrayDate['dateTo']
            ])->setPaper('A4', 'landscape');

            return $this->from(env('MAIL_FROM_ADDRESS'))
                ->subject('Fe de erratas: Rendición de comisiones (Periodo ' . $this->arrayDate['dateFrom'] . ' – ' . $this->arrayDate['dateTo'] . ' )')
                ->view('emails.provider.summary_fe',
                    [
                        'provider' => $this->provider,
                        'dateFrom' => $this->arrayDate['dateFrom'],
                        'dateTo' => $this->arrayDate['dateTo']
                    ])
                ->attachData($pdfContent->output(), 'RENDICION_COMISIONES' . '.pdf');

        } catch (\Exception $e) {
            Log::error(__class__ . ": no se pudo enviar el mail de " . $this->provider['id']);
            return response()->json([
                'error' => true,
                'message' => 'Error ' . __class__ . ' ' . $e->getMessage()
            ]);
        }
    }
}

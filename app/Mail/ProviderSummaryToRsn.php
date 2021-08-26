<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ProviderSummaryToRsn extends Mailable
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
            $pdfContent = $pdf->loadView('provider.summaryToRsn', [
                'provider' => $this->provider,
                'dateGenerated' => $this->arrayDate['dateGenerated'],
                'dateFrom' => $this->arrayDate['dateFrom'],
                'dateTo' => $this->arrayDate['dateTo']
            ])->setPaper('A4', 'landscape');

            return $this->from(env('MAIL_FROM_ADDRESS'))
                ->subject('Rendición de comisiones RSN - ' . $this->provider["first_name"] . $this->provider["last_name"]  . ' (Periodo ' . $this->arrayDate['dateFrom'] . ' – ' . $this->arrayDate['dateTo'] . ' )')
                ->view('emails.provider.summaryToRsn',
                    [
                        'provider' => $this->provider,
                        'dateFrom' => $this->arrayDate['dateFrom'],
                        'dateTo' => $this->arrayDate['dateTo']
                    ])
                ->attachData($pdfContent->output(), 'RENDICION_COMISIONES_RSN__' . $this->provider["first_name"] . $this->provider["last_name"]  . '.pdf');

        } catch (\Exception $e) {
            Log::error(__class__ . ": no se pudo enviar el mail de emprendedor " . $this->provider['id'] . " - comisiones RSN");
            return response()->json([
                'error' => true,
                'message' => 'Error ' . __class__ . ' ' . $e->getMessage()
            ]);
        }
    }
}

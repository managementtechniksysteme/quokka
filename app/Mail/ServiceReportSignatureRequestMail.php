<?php

namespace App\Mail;

use App\Models\ServiceReport;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceReportSignatureRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $serviceReport;
    public $services_min_provided_on;
    public $services_max_provided_on;
    public $services_sum_hours;
    public $services_sum_kilometres;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ServiceReport $serviceReport)
    {
        $this->serviceReport = $serviceReport;
        // workaround for loaded aggregates not working in queues
        $this->services_min_provided_on = Carbon::parse($serviceReport->services_min_provided_on);
        $this->services_max_provided_on = Carbon::parse($serviceReport->services_max_provided_on);
        $this->services_sum_hours = $serviceReport->services_sum_hours;
        $this->services_sum_kilometres = $serviceReport->services_sum_kilometres;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Servicebericht unterschreiben ('.$this->serviceReport->project->name.' #'.$this->serviceReport->number.')')
            ->markdown('emails.service_report.signature_request');
    }
}

<?php

namespace Gdinko\Econt\Actions;

use Gdinko\Econt\Hydrators\Payment;

trait ManagesPaymentReports
{
    /**
     * paymentReport
     *
     * @param  \Gdinko\Econt\Hydrators\Payment $payment
     * @return array
     */
    public function paymentReport(Payment $payment): array
    {
        return $this->post(
            'PaymentReport/PaymentReportService.PaymentReport.json',
            $payment->validated(),
        )['PaymentReportRows'] ?? [];
    }
}

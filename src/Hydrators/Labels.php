<?php

namespace Gdinko\Econt\Hydrators;

use Gdinko\Econt\Exceptions\EcontValidationException;
use Illuminate\Support\Facades\Validator;

class Labels
{
    protected $labels = [];

    protected $runAsyncAndEmailResultTo = null;

    protected $mode = null;

    /**
     * __construct
     *
     * @param  array $labels
     * @param  string $mode
     * @param  string $runAsyncAndEmailResultTo
     *
     * @return void
     */
    public function __construct(
        array $labels,
        $mode,
        $runAsyncAndEmailResultTo = null
    ) {
        $this->labels = $labels;

        $this->mode = $mode;

        $this->runAsyncAndEmailResultTo = $runAsyncAndEmailResultTo;
    }

    /**
     * validationRules
     *
     * @return array
     */
    public function validationRules(): array
    {
        return [
            '*.shipmentNumber' => 'string|sometimes',
            '*.previousShipmentNumber' => 'string|sometimes',
            '*.previousShipmentReceiverPhone' => 'string|sometimes',
            '*.senderClient' => 'array|required',
            '*.senderAgent' => 'array|sometimes',
            '*.senderAddress' => 'array|sometimes',
            '*.senderOfficeCode' => 'string|sometimes',
            '*.emailOnDelivery' => 'string|sometimes',
            '*.smsOnDelivery' => 'string|sometimes',
            '*.receiverClient' => 'array|sometimes',
            '*.receiverAgent' => 'array|sometimes',
            '*.receiverAddress' => 'array|sometimes',
            '*.receiverOfficeCode' => 'string|sometimes',
            '*.receiverProviderID' => 'numeric|sometimes',
            '*.receiverBIC' => 'string|sometimes',
            '*.receiverIBAN' => 'string|sometimes',
            '*.envelopeNumbers' => 'string|sometimes',
            '*.packCount' => 'numeric|sometimes',
            '*.packs' => 'array|sometimes',
            '*.shipmentType' => 'string|sometimes',
            '*.weight' => 'numeric|sometimes',
            '*.sizeUnder60cm' => 'boolean|sometimes',
            '*.shipmentDimensionsL' => 'numeric|sometimes',
            '*.shipmentDimensionsW' => 'numeric|sometimes',
            '*.shipmentDimensionsH' => 'numeric|sometimes',
            '*.shipmentDescription' => 'string|sometimes',
            '*.orderNumber' => 'string|sometimes',
            '*.sendDate' => 'string|sometimes',
            '*.holidayDeliveryDay' => 'string|sometimes',
            '*.keepUpright' => 'boolean|sometimes',
            '*.services' => 'array|sometimes',
            '*.instructions' => 'array|sometimes',
            '*.payAfterAccept' => 'boolean|sometimes',
            '*.payAfterTest' => 'boolean|sometimes',
            '*.packingListType' => 'string|sometimes',
            '*.packingList' => 'array|sometimes',
            '*.partialDelivery' => 'boolean|sometimes',
            '*.paymentSenderMethod' => 'string|sometimes',
            '*.paymentReceiverMethod' => 'string|sometimes',
            '*.paymentReceiverAmount' => 'numeric|sometimes',
            '*.paymentReceiverAmountIsPercent' => 'boolean|sometimes',
            '*.paymentOtherClientNumber' => 'string|sometimes',
            '*.paymentOtherAmount' => 'numeric|sometimes',
            '*.paymentOtherAmountIsPercent' => 'boolean|sometimes',
            '*.mediator' => 'string|sometimes',
            '*.paymentToken' => 'string|sometimes',
        ];
    }

    /**
     * validated
     *
     * @return array
     *
     * @throws Exception
     */
    public function validated(): array
    {
        $validator = Validator::make(
            $this->labels,
            $this->validationRules()
        );

        if ($validator->fails()) {
            throw new EcontValidationException(
                Label::class,
                422,
                $validator->messages()->toArray()
            );
        }

        return [
            'labels' => $validator->validated(),
            'mode' => $this->mode,
            'runAsyncAndEmailResultTo' => $this->runAsyncAndEmailResultTo,
        ];
    }
}

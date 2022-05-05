<?php

namespace Gdinko\Econt\Hydrators;

use Gdinko\Econt\Exceptions\EcontValidationException;
use Illuminate\Support\Facades\Validator;

class Label
{
    protected $label = [];

    protected $requestCourierTimeFrom = null;

    protected $requestCourierTimeTo = null;

    protected $mode = null;

    /**
     * __construct
     *
     * @param  array $label
     * @param  string $mode
     * @param  string $requestCourierTimeFrom
     * @param  string $requestCourierTimeTo
     *
     * @return void
     */
    public function __construct(
        array $label,
        $mode,
        $requestCourierTimeFrom = null,
        $requestCourierTimeTo = null
    ) {
        $this->label = $label;

        $this->mode = $mode;

        $this->requestCourierTimeFrom = $requestCourierTimeFrom;

        $this->requestCourierTimeTo = $requestCourierTimeTo;
    }

    /**
     * validationRules
     *
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'shipmentNumber' => 'string|nullable',
            'previousShipmentNumber' => 'string|nullable',
            'previousShipmentReceiverPhone' => 'string|nullable',
            'senderClient' => 'array|required',
            'senderAgent' => 'array|nullable',
            'senderAddress' => 'array|nullable',
            'senderOfficeCode' => 'string|nullable',
            'emailOnDelivery' => 'string|nullable',
            'smsOnDelivery' => 'string|nullable',
            'receiverClient' => 'array|nullable',
            'receiverAgent' => 'array|nullable',
            'receiverAddress' => 'array|nullable',
            'receiverOfficeCode' => 'string|nullable',
            'receiverProviderID' => 'numeric|nullable',
            'receiverBIC' => 'string|nullable',
            'receiverIBAN' => 'string|nullable',
            'envelopeNumbers' => 'string|nullable',
            'packCount' => 'numeric|nullable',
            'packs' => 'array|nullable',
            'shipmentType' => 'string|nullable',
            'weight' => 'numeric|nullable',
            'sizeUnder60cm' => 'boolean|nullable',
            'shipmentDimensionsL' => 'numeric|nullable',
            'shipmentDimensionsW' => 'numeric|nullable',
            'shipmentDimensionsH' => 'numeric|nullable',
            'shipmentDescription' => 'string|nullable',
            'orderNumber' => 'string|nullable',
            'sendDate' => 'string|nullable',
            'holidayDeliveryDay' => 'string|nullable',
            'keepUpright' => 'boolean|nullable',
            'services' => 'array|nullable',
            'instructions' => 'array|nullable',
            'payAfterAccept' => 'boolean|nullable',
            'payAfterTest' => 'boolean|nullable',
            'packingListType' => 'string|nullable',
            'packingList' => 'array|nullable',
            'partialDelivery' => 'boolean|nullable',
            'paymentSenderMethod' => 'string|nullable',
            'paymentReceiverMethod' => 'string|nullable',
            'paymentReceiverAmount' => 'numeric|nullable',
            'paymentReceiverAmountIsPercent' => 'boolean|nullable',
            'paymentOtherClientNumber' => 'string|nullable',
            'paymentOtherAmount' => 'numeric|nullable',
            'paymentOtherAmountIsPercent' => 'boolean|nullable',
            'mediator' => 'string|nullable',
            'paymentToken' => 'string|nullable',
        ];
    }

    /**
     * validated
     *
     * @return array
     *
     * @throws EcontValidationException
     */
    public function validated(): array
    {
        $validator = Validator::make(
            $this->label,
            $this->validationRules()
        );

        if ($validator->fails()) {
            throw new EcontValidationException(
                __CLASS__,
                422,
                $validator->messages()->toArray()
            );
        }

        return [
            'label' => $validator->validated(),
            'mode' => $this->mode,
            'requestCourierTimeFrom' => $this->requestCourierTimeFrom,
            'requestCourierTimeTo' => $this->requestCourierTimeTo,
        ];
    }
}

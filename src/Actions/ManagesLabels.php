<?php

namespace Gdinko\Econt\Actions;

use Gdinko\Econt\Hydrators\Label;
use Gdinko\Econt\Hydrators\Labels;

trait ManagesLabels
{
    /**
     * createLabel
     *
     * @param  \Gdinko\Econt\Hydrators\Label $label
     * @return array
     *
     * @throws Exception
     */
    public function createLabel(Label $label): array
    {
        return $this->post(
            'Shipments/LabelService.createLabel.json',
            $label->validated(),
        );
    }

    /**
     * createLabels
     *
     * @param  \Gdinko\Econt\Hydrators\Labels $labels
     * @return array
     *
     * @throws Exception
     */
    public function createLabels(Labels $labels): array
    {
        return $this->post(
            'Shipments/LabelService.createLabels.json',
            $labels->validated(),
        );
    }

    /**
     * deleteLabels
     *
     * @param  array $shipmentNumbers
     * @return array
     *
     * @throws Exception
     */
    public function deleteLabels(array $shipmentNumbers): array
    {
        return $this->post(
            'Shipments/LabelService.deleteLabels.json',
            ['shipmentNumbers' => $shipmentNumbers]
        );
    }

    /**
     * updateLabel
     *
     * @param  \Gdinko\Econt\Hydrators\Label $label
     * @return array
     *
     * @throws Exception
     */
    public function updateLabel(Label $label): array
    {
        return $this->post(
            'Shipments/LabelService.updateLabel.json',
            $label->validated(),
        );
    }
}

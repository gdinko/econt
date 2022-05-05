<?php

namespace Gdinko\Econt\Actions;

trait ManagesProfile
{    
    /**
     * getClientProfiles
     *
     * @return array
     */
    public function getClientProfiles(): array
    {
        return $this->post(
            'Profile/ProfileService.getClientProfiles.json',
            ['GetClientProfilesRequest' => ''],
        )['profiles'];
    }
}

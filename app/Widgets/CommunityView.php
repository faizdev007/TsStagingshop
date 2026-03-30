<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use App\Models\Community;
use App\Property;

class CommunityView extends AbstractWidget
{
    protected $config = [
        'template' => 1,
        'community' => true,
        'property' => null,
    ];

    /**
     * Find community information based on property data
     */
    protected function findCommunityInformation($property)
    {
        if (!$property instanceof Property) {
            return null;
        }

        $communityName = $property->street ?? $property->town ?? null;

        if (!$communityName) {
            return null;
        }

        return Community::where('name', $communityName)
            ->where('is_publish', 1)
            ->first();
    }

    public function run()
    {
        $themeDirectory = themeOptions();
        $community = null;

        if (!empty($this->config['property'])) {
            $community = $this->findCommunityInformation($this->config['property']);
        }

        return view(
            'frontend.' . $themeDirectory . '.widgets.community_information',
            [
                'config' => $this->config,
                'community' => $community,
            ]
        );
    }
}

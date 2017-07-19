<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;

class LeadDimmer extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {
        $count = \App\Lead::count();
        $string = $count == 1 ? 'lead' : 'leads';
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-people',
            'title'  => "{$count} {$string}",
            'text'   => "You have {$count} {$string} in your database. Click on button below to view all users.",
            'button' => [
                'text' => 'View all leads',
                'link' => '/admin/leads',
            ],
            'image' => voyager_asset('images/widget-backgrounds/04.png'),
        ]));
    }
}

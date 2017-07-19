<?php

namespace App\Widgets;

use Arrilot\Widgets\AbstractWidget;
use TCG\Voyager\Facades\Voyager;

class ClientDimmer extends AbstractWidget
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
        $count = \App\Client::count();
        $string = $count == 1 ? 'client' : 'clients';
        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-company',
            'title'  => "{$count} {$string}",
            'text'   => "You have {$count} {$string} in your database. Click on button below to view all users.",
            'button' => [
                'text' => 'View all clients',
                'link' => '/admin/clients',
            ],
            'image' => voyager_asset('images/widget-backgrounds/03.png'),
        ]));
    }
}

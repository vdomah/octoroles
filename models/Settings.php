<?php namespace Vdomah\Roles\Models;

use October\Rain\Database\Model;
use System\Classes\PluginManager;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];
    public $settingsCode = 'vdomah_roles_settings';
    public $settingsFields = 'fields.yaml';

    public function getUserPluginOptions()
    {
        $options = [];

        if (PluginManager::instance()->exists('RainLab.User'))
            $options['RainLab.User'] = 'RainLab.User';

        if (PluginManager::instance()->exists('Lovata.Buddies'))
            $options['Lovata.Buddies'] = 'Lovata.Buddies';

        return $options;
    }
}
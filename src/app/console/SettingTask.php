<?php

// declare(strict_types=1);

namespace MyApp\Console;

use Phalcon\Cli\Task;
use Firebase\JWT\JWT;
use Settings;

class SettingTask extends Task
{
    public function setDefaultPriceAction($price = 0)
    {
        $setting = Settings::findFirst();
        $setting->price = $price;
        $setting->save();
        echo 'Default price is set to ' . $price;
    }
    public function setDefaultStockAction($stock = 0)
    {
        $setting = Settings::findFirst();
        $setting->stock = $stock;
        $setting->save();
        echo 'Default stock is set to ' . $stock;
    }
    public function removeAclAction()
    {
        $aclFile = APP_PATH . '/security/acl.cache';
        unlink($aclFile);
    }
    public function removeLogsAction()
    {
        
        $files = glob(APP_PATH . '/storage/logs/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
}

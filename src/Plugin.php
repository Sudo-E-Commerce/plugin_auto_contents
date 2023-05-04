<?php

namespace Sudo\AutoContent;

use Sudo\PluginManagement\Abstracts\PluginOperationAbstract;
use Schema;

class Plugin extends PluginOperationAbstract
{
    public static function remove()
    {
        Schema::dropIfExists('ac_keywords');
        Schema::dropIfExists('ac_outlines');
    }
}

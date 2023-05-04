<?php
 
namespace Sudo\AutoContent\Providers;
 
use Illuminate\Support\ServiceProvider;
use File;

class AutoContentServiceProvider extends ServiceProvider
{
    /**
     * Register config file here
     * alias => path
     */
    private $configFile = [
        'SudoMenu' => 'SudoMenu.php',
        'SudoModule' => 'SudoModule.php',
    ];

    /**
     * Register commands file here
     * alias => path
     */
    protected $commands = [
        //
    ];

	/**
     * Register bindings in the container.
     */
    public function register()
    {
        // Đăng ký config cho từng Module
        $this->mergeConfig();
        // boot commands
        $this->commands($this->commands);
    }

	public function boot()
	{
		$this->registerModule();

        $this->publish();
	}

	private function registerModule() {
		$modulePath = __DIR__.'/../../';
        $moduleName = 'AutoContent';

        // boot route
        if (File::exists($modulePath."routes/routes.php")) {
            $this->loadRoutesFrom($modulePath."/routes/routes.php");
        }

        // boot migration
        if (File::exists($modulePath . "migrations")) {
            $this->loadMigrationsFrom($modulePath . "migrations");
        }

        // boot languages
        if (File::exists($modulePath . "resources/lang")) {
            $this->loadTranslationsFrom($modulePath . "resources/lang", $moduleName);
            $this->loadJSONTranslationsFrom($modulePath . 'resources/lang');
        }

        // boot views
        if (File::exists($modulePath . "resources/views")) {
            $this->loadViewsFrom($modulePath . "resources/views", $moduleName);
        }

	    // boot all helpers
        if (File::exists($modulePath . "helpers")) {
            // get all file in Helpers Folder 
            $helper_dir = File::allFiles($modulePath . "helpers");
            // foreach to require file
            foreach ($helper_dir as $key => $value) {
                $file = $value->getPathName();
                require $file;
            }
        }
	}

    /*
    * publish dự án ra ngoài
    * publish config File
    * publish assets File
    */
    public function publish()
    {
        if ($this->app->runningInConsole()) {
            $assets = [
                //
            ];
            $config = [
                //
            ];
            $all = array_merge($assets, $config);
            // Chạy riêng
            $this->publishes($all, 'sudo/auto_content');
            $this->publishes($assets, 'sudo/auto_content/assets');
            $this->publishes($config, 'sudo/auto_content/config');
        }
    }

    /*
    * Đăng ký config cho từng Module
    * $this->configFile
    */
    public function mergeConfig() {
        foreach ($this->configFile as $alias => $path) {
            $config = $this->app['config']->get($alias, []);
            if($alias == 'SudoMenu') {
                $menu = require __DIR__ . "/../../config/" . $path;
                $index = array_search('package_plugin_manager', array_keys($config['menu']));
                $newConfig = array_merge(array_slice($config['menu'], 0, $index + 1), $menu['menu'], array_slice($config['menu'], $index + 1));
                $config = ['menu' => $newConfig];
                $this->app['config']->set($alias, $config);
            } else {
                $this->app['config']->set($alias, $this->mergeArrayConfigs(require __DIR__ . "/../../config/" . $path, $config));
            }
        }
    }

    /**
     * Merge config để lấy ra mảng chung
     * Ưu tiên lấy config ở app
     * @param  array  $original
     * @param  array  $merging
     * @return array
     */
    protected function mergeArrayConfigs(array $original, array $merging)
    {
        $array = array_merge($original, $merging);
        foreach ($original as $key => $value) {
            if (! is_array($value)) { continue; }
            if (! \Arr::exists($merging, $key)) { continue; }
            if (is_numeric($key)) { continue; }
            $array[$key] = $this->mergeArrayConfigs($value, $merging[$key]);
        }
        return $array;
    }
}
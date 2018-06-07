<?php

namespace GeneroWP\Common;

trait Assets
{
    public function enqueueStyle(...$params)
    {
        $this->run('wp_enqueue_style', ...$params);
    }

    public function enqueueScript(...$params)
    {
        $this->run('wp_enqueue_script', ...$params);
    }

    public function registerStyle(...$params)
    {
        $this->run('wp_register_style', ...$params);
    }

    public function registerScript(...$params)
    {
        $this->run('wp_register_script', ...$params);
    }

    protected function run($function, $handle, $path = null, $dependencies = [], $version = false) {
        if (!$path) {
            $function($handle);
        }
        if (filter_var($path, FILTER_VALIDATE_URL) === false) {
            // Local file
            $url = $this->plugin_url . $path;
            $path = $this->plugin_path . $path;
            $version = empty($version) ? filemtime($path) : $version;
            $function($handle, $url, $dependencies, $version);
        } else {
            // Remote file
            $function($handle, $path, $dependencies);
        }
    }
}

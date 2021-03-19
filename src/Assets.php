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

    public function localizeScript($handle, $data, $plugin_name = null)
    {
        if (is_null($plugin_name)) {
            $plugin_name = $this->plugin_name;
        }
        if (!empty($data)) {
            $script = 'wp.i18n.setLocaleData(' . json_encode($data) . ', "' . $plugin_name . '");';
            wp_add_inline_script($handle, $script, 'before');
        }
    }

    protected function run($function, $handle, $path = null, $dependencies = [], $version = false, ...$params) {
        if (!$path) {
            $function($handle);
        }
        if (filter_var($path, FILTER_VALIDATE_URL) === false) {
            // Local file
            $url = $this->plugin_url . $path;
            $path = $this->plugin_path . $path;
            $version = empty($version) ? filemtime($path) : $version;
            $function($handle, $url, $dependencies, $version, ...$params);
        } else {
            // Remote file
            $function($handle, $path, $dependencies, ...$params);
        }
    }
}

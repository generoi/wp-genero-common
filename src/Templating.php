<?php

namespace GeneroWP\Common;

trait Templating
{
    public function template($handle, $slug, $attributes = [])
    {
        $templates = [
            "$slug.php",
        ];

        $templates = apply_filters("$handle/template_hierarchy", array_reverse(array_merge($templates, array_map(function ($template) use ($handle) {
            return $handle . '/' . $template;
        }, $templates))));

        // WP Timber Extended support
        $templates = apply_filters('timber_template_hierarchy', $templates);

        $template = locate_template($templates);
        if ($template = apply_filters("$handle/template", $template)) {
            // If it's not twig, render as php
            if (substr($template, -5) !== '.twig') {
                return $this->renderPhpTemplate($template, $attributes);
            }
            // If the path is absolute, use the relative path from the theme.
            $template = str_replace(TEMPLATEPATH, '', $template);
            return \Timber::fetch($template, $attributes);
        }
        return $this->renderPhpTemplate($this->getTemplateDir() . "/$slug.php", $attributes);
    }


    protected function getTemplateDir()
    {
        return dirname((new \ReflectionClass(static::class))->getFileName()) . '/views';
    }

    protected function renderPhpTemplate($path, $attributes)
    {
        extract($attributes);
        ob_start();
        include $path;
        return ob_get_clean();
    }
}

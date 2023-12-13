<?php

namespace Core;

class View
{
    protected $viewPath;
    protected $base_url;

    public function __construct($base_url = null)
    {
        $this->viewPath = base_path("src/views/");
        $this->base_url = $base_url ?? "/";
    }

    /**
     * Renders a view file by including its content and outputting it to the browser.
     *
     * @param string $view The name of the view file to be rendered.
     * @param array $optionals An optional array of additional parameters.
     *                         - 'path' (string): The path to the view file.
     *                         - 'data' (array): Additional data to be passed to the view file.
     *                         - 'metadata' (array): Metadata for the view file, such as CSS and JS files.
     * @throws \Exception If the view file is not found.
     * @return void
     */
    public function render($view, $optionals = [])
    {
        if (isset($optionals['path'])) {
            $optionals['path'] = $optionals['path'] . '/';
        } else {
            $optionals['path'] = '';
        }
        $viewFile = $this->viewPath . $optionals['path'] . $view . '.php';
        $data = $optionals['data'] ?? [];
        $data['viewPath'] = $this->viewPath;
        $data['app'] = Application::$app;
        $data['base_url'] = $this->base_url;

        if (file_exists($viewFile)) {
            ob_start();
            extract($data);
            require $viewFile;
            $content = ob_get_clean();

            // Render header
            $metadata = $optionals['metadata'] ?? [];
            $this->renderHeader($metadata);

            // Output the view content
            echo $content;
        } else {
            throw new \Exception("View file not found: $view");
        }
    }

    /**
     * Renders the header file of a view by including its content and outputting it to the browser.
     *
     * @param array $metadata An array containing metadata for the view file, such as CSS and JS files.
     * @return void
     */
    protected function renderHeader($metadata)
    {
        if (isset($metadata['cssFiles'])) {
            foreach ($metadata['cssFiles'] as $key => $value) {
                $metadata['cssFiles'][$key] = '/css/' . $value;
            }
        }
        if (isset($metadata['jsFiles'])) {
            foreach ($metadata['jsFiles'] as $key => $value) {
                $metadata['jsFiles'][$key] = '/js/' . $value;
            }
        }
        $headerFile = $this->viewPath . 'partials/header.php';
        if (file_exists($headerFile)) {
            extract($metadata);
            require $headerFile;
        }
    }
}

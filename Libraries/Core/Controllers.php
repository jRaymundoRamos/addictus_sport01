<?php

class Controllers
{
    protected Views $views;
    protected object|null $model = null;

    public function __construct()
    {
        $this->views = new Views();
        $this->loadModel();
    }

    protected function loadModel(): void
    {
        $modelClass = get_class($this) . "Model";
        $modelPath = __DIR__ . "/../../Models/" . $modelClass . ".php";

        if (file_exists($modelPath)) {
            require_once $modelPath;

            if (class_exists($modelClass)) {
                $this->model = new $modelClass();
            }
        }
    }
}

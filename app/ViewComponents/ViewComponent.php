<?php

namespace App\ViewComponents;

abstract class ViewComponent
{
    /**
     * The collection of data for this view component.
     *
     * @var \Illuminate\Support\Collection
     */
    public $data;

    /**
     * The view file used to render the component.
     *
     * @var string
     */
    public $view;

    /**
     * Called when the class should load.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = collect($data);
    }

    /**
     * Renders the component markup.
     *
     * @return string
     */
    public function render()
    {
        if (is_null($this->view)) {
            throw new \RuntimeException('Please specify a view property for this view component.');
        }

        return view($this->view, $this->getViewData())->render();
    }

    /**
     * Gets the data which should be used to render the component's markup.
     *
     * @return array
     */
    public function getViewData()
    {
        return ['component' => $this];
    }

    /**
     * Handles dynamic get actions.
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->data->get($key);
    }

    /**
     * Handles dynamic set actions.
     *
     * @param string $key
     * @param mixed $key
     * @return mixed
     */
    public function __set($key, $value)
    {
        return $this->data->set($key, $value);
    }

    /**
     * Handles dynamic call actions.
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function __call($method, $args)
    {
        return $this->data->{$method}(...$args);
    }
}

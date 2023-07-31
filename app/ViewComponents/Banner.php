<?php

namespace App\ViewComponents;

class Banner extends ViewComponent
{
    /**
     * The view file used to render the component.
     *
     * @var string
     */
    public $view = 'frontend.components.banner';

    /**
     * Checks if the CTA button should be displayed.
     *
     * @return boolean
     */
    public function shouldDisplayCta()
    {
        return !empty($this->data['cta_link']) && !empty($this->data['cta_text']);
    }

    /**
     * Checks if the icon should be displayed.
     *
     * @return boolean
     */
    public function shouldDisplayIcon()
    {
        return !empty($this->data['icon']);
    }

}

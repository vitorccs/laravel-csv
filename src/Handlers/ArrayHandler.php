<?php

namespace Vitorccs\LaravelCsv\Handlers;

class ArrayHandler implements Handler
{
    /**
     * @var array
     */
    protected array $handler = [];

    /**
     * @return array
     */
    public function getResource()
    {
        return $this->handler;
    }

    /**
     * @param array $content
     * @return void
     */
    public function addContent(array $content): void
    {
        $this->handler[] = $content;
    }
}

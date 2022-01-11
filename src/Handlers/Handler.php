<?php

namespace Vitorccs\LaravelCsv\Handlers;

interface Handler
{
    public function getResource();

    public function addContent(array $content): void;
}

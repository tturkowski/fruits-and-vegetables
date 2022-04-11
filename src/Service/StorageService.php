<?php

namespace App\Service;

class StorageService
{
    protected string $request = '';

    # We do receive var/request.json request
    # Our task is to process the data and aggregate them into fruit and vegetables collections
    # Keep in mind to keep the consistency of units
    public function __construct(
        string $request
    )
    {
        $this->request = $request;
    }

    public function getRequest(): string
    {
        return $this->request;
    }
}

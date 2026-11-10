<?php

namespace App\ApiResponse;

class ApiResponseBuilder
{
    public $response;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->response=new ApiResponse();
    }

    public function withMessage($message): static
    {
        $this->response->setMessage($message);
        return $this;
    }

    public function withToken($token): static
    {
        $this->response->setToken($token);
        return $this;
    }
    public function withData($data): static
    {
        $this->response->setData($data);
        return $this;
    }
    public function withStatus($status): static
    {
        $this->response->setStatus($status);
        return $this;
    }

    public function withAppend($append): static
    {
        $this->response->setAppend($append);
        return $this;
    }

    public function build(): ApiResponse
    {
        return $this->response;
    }
}

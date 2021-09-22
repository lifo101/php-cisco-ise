<?php


namespace Lifo\CiscoISE;


use RuntimeException;

class ISEError extends RuntimeException
{
    private ?object $error;

    public function __construct(?object $error, string $message = '')
    {
        $msg = $message ?: '';
        if ($error) {
            $this->error = $error;
            $errors = $this->getErrorMessage();
            if ($errors) {
                if ($msg) $msg .= ': ';
                $msg .= $errors;
            }
        }
        parent::__construct($msg);
    }

    public function getError(): ?object
    {
        return $this->error;
    }

    private function getErrorMessage(): string
    {
        $msg = [];
        foreach ($this->error->messages as $err) {
            $msg[] = $err->title;
        }
        return implode('. ', $msg);
    }

}
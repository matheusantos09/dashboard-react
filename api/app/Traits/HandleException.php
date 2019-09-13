<?php

namespace Module\Core\Traits;

/**
 * Trait Functions
 *
 * @package App\Traits
 */
trait HandleException
{
    /**
     * Trata como a exception vai ser devolvida
     *
     * @param      $exception
     * @param bool $fullException
     *
     * @return mixed
     */
    private function responseException($exception, $fullException = false)
    {

        if ($fullException) {
            return $exception;
        }

        return $exception->getMessage();

    }
}
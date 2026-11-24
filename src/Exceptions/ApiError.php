<?php

namespace Plokko\LaravelBulkgate\Exceptions;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Client\RequestException;
use JsonSerializable;
use Throwable;

class ApiError extends \Exception implements Arrayable, JsonSerializable
{
    public function __construct(
        public readonly ?string $type,
        int $code,
        public readonly ?string $error,
        public readonly ?string $detail,
        ?Throwable $previous = null,
    ) {
        parent::__construct(message: $error.($detail ? ": $detail" : ''), code: $code, previous: $previous);
    }

    /**
     * ApiError factory from RequestException
     */
    public static function fromRequestException(RequestException $ex): static
    {
        $data = $ex->response?->json();

        return new ApiError(
            type: $data['type'] ?? null,
            code: $data['code'] ?? 0,
            error: $data['error'] ?? $ex->getMessage(),
            detail: $data['detail'] ?? null,
            previous: $ex,
        );
    }

    public function toArray()
    {
        return [
            'type' => $this->type,
            'code' => $this->code,
            'error' => $this->error,
            'detail' => $this->detail,
        ];
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}

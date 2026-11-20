<?php

namespace Plokko\LaravelBulkgate\Facades;

use Illuminate\Support\Facades\Facade;
use Plokko\LaravelBulkgate\BulkgateInterface;

/**
 * BulkGate API facade.
 *
 * @method Transactional(): \Plokko\LaravelBulkgate\RequestBuilders\TransactionalMessageBuilder
 * @method Info(): \Plokko\LaravelBulkgate\RequestBuilders\InfoBuilder
 * @method CheckNumbers(): \Plokko\LaravelBulkgate\RequestBuilders\NumberCheckerBuilder
 */
class BulkGate extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BulkgateInterface::class;
    }
}

<?php

namespace Plokko\LaravelBulkgate\Console\Commands;

use Illuminate\Console\Command;
use Plokko\LaravelBulkgate\Facades\BulkGate;

class CheckNumbers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulkgate:check-numbers {numbers*} {--country=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get current BulkGate balance.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $numbers = $this->argument('numbers');
        $country = $this->option('country');

        $result = BulkGate::CheckNumbers()
            ->addNumber($numbers, empty($country) ? null : $country)
            ->check();

        $list = array_map(function ($e) {
            $row = $e->toArray();
            $row['valid'] = $row['valid'] === null ? '' : ($row['valid'] ? 'Yes' : 'No');

            return $row;
        }, $result->toArray());

        $this->table(array_keys(current($list)), $list);

        return self::SUCCESS;
    }
}

<?php

namespace Plokko\LaravelBulkgate\Console\Commands;

use Illuminate\Console\Command;
use Plokko\LaravelBulkgate\Facades\BulkGate;

class GetBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bulkgate:get-balance';

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
        $balance = BulkGate::Info()->getBalance();

        $this->info('Current balance:');
        $this->fieldTable($balance->toArray());

        return self::SUCCESS;
    }

    protected function fieldTable(array $data)
    {
        $headers = ['Field', 'Value'];
        $rows = [];
        foreach ($data as $field => $value) {
            $rows[] = [$field, $value];
        }
        $this->table($headers, $rows);
    }
}

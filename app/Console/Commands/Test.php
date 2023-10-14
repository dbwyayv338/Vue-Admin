<?php

namespace App\Console\Commands;

use Finance\Models\FinApproval;
use Finance\Models\FinOrder;
use Data\Models\Customer;
use Finance\Models\FinOrderVoucher;
use Illuminate\Console\Command;
use Mall\Models\ActBargain;
use Mall\Models\RentOrder;
use Supply\Models\SupplyInstallmentOrder;
use Illuminate\Support\Facades\DB;
use Supply\Models\SupplyTask;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

    }

}

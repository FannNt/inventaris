<?php

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\User;
use App\Notifications\ItemExpiryNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending email to admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $admins = User::all()->where('is_admin', true);
        $threeMonthsFromNow = Carbon::now()->addMonths(3);
        $expItems = Item::query()
            ->where('masa_berlaku', '<', $threeMonthsFromNow);

        if (!$expItems) {
            $this->info('No expiring items found.');
            return;
        }

        try {
            foreach ($admins as $admin) {
                $admin->notify(new ItemExpiryNotification($expItems));
            }

        }catch (\Exception $exception){
            $this->error('Got error: ' . $exception->getMessage());
        }



    }
}

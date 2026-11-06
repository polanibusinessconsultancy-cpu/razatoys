<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\OneStopMartCatalogImporter;

class ImportOneStopMartCatalog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'one-stop-mart:import 
                            {--dry-run : Perform a dry run without committing database changes}
                            {--pk-rate=280 : Conversion rate from template USD price to PKR}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import One Stop Mart products, categories, and update branding configurations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $rate = (float)$this->option('pk-rate');

        $this->info("Starting One Stop Mart catalog import...");
        if ($dryRun) {
            $this->warn("!!! DRY RUN MODE ACTIVE - NO CHANGES WILL BE WRITTEN !!!");
        }
        $this->info("Conversion rate used: 1 USD = Rs. {$rate}");

        $importer = new OneStopMartCatalogImporter();

        try {
            $result = $importer->import($rate, $dryRun);
            
            $this->newLine();
            $this->info("----- Import Summary -----");
            $this->line("Categories Reused: " . $result['counts']['categories_reused']);
            $this->line("Categories Created: " . $result['counts']['categories_created']);
            $this->line("Products Inserted: " . $result['counts']['products_inserted']);
            $this->line("Products Updated: " . $result['counts']['products_updated']);
            $this->line("Products Skipped: " . $result['counts']['products_skipped']);
            $this->line("Products Failed: " . $result['counts']['products_failed']);
            $this->line("Old Polani Products Hidden: " . $result['counts']['old_products_hidden']);
            $this->line("Old Blogs Hidden: " . $result['counts']['old_blogs_hidden']);
            
            $this->newLine();
            $this->info("----- Detailed Log -----");
            foreach ($result['log'] as $line) {
                if (str_contains($line, 'failed') || str_contains($line, 'failed:')) {
                    $this->error($line);
                } else {
                    $this->line($line);
                }
            }

            if ($result['counts']['products_failed'] > 0) {
                $this->error("Some products failed validation or asset checks! Import completed with errors.");
                return 1;
            }

            $this->info("One Stop Mart catalog import process completed successfully.");
            return 0;

        } catch (\Exception $e) {
            $this->error("Import command failed with exception: " . $e->getMessage());
            return 1;
        }
    }
}

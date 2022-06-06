<?php

namespace App\Console\Commands;
use App\Imports\ImportObf;
use Illuminate\Console\Command;

class ImportObfCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:excel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Laravel Excel importer';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->output->title('Starting import');
        (new ImportObf)->withOutput($this->output)->import('obf.xlsx');
        $this->output->success('Import successful');
    }
}

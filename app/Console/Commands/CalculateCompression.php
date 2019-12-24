<?php

namespace App\Console\Commands;

use App\Session;
use Illuminate\Console\Command;

class CalculateCompression extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'compress:calculate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

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
	 * @return mixed
	 */
	public function handle()
	{
		$current = 0;
		$gzip = 0;
		$gzip2 = 0;
		$bzip = 0;
		$bzip2 = 0;

		$cursor = Session::query()->cursor();

		/** @var Session $session */
		foreach ($cursor as $session) {
			$data = $session->metrics;
			$gzipped = gzdeflate($data, 9);
			$gzipped2 = gzdeflate($gzipped, 9);
			$bzipped = bzcompress($data, 9);
			$bzipped2 = bzcompress($bzipped, 9);

			$current += strlen($data);
			$gzip += strlen($gzipped);
			$gzip2 += strlen($gzipped2);
			$bzip += strlen($bzipped);
			$bzip2 += strlen($bzipped2);
		}

		$bzipRatio = number_format($bzip / $current * 100, 1);
		$bzip2Ratio = number_format($bzip2 / $current * 100, 1);
		$gzip2Ratio = number_format($gzip2 / $current * 100, 1);
		$gzipRatio = number_format($gzip / $current * 100, 1);

		$this->info("Total raw data: $current bytes");
		$this->info("Total bzip (1x): $bzip bytes $bzipRatio %");
		$this->info("Total bzip (2x): $bzip2 bytes $bzip2Ratio %");
		$this->info("Total gzip (1x): $gzip bytes $gzipRatio %");
		$this->info("Total gzip (2x): $gzip2 bytes $gzip2Ratio %");
	}
}

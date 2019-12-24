<?php

namespace Tests\Unit;

use App\Classes\SessionBuilder;
use App\Session;
use App\Stats\Damage\DamagePerHitGroupStat;
use App\Stats\Damage\DamagePerWeaponStat;
use App\Stats\Hits\HitsPerHitGroupStat;
use App\Stats\Hits\HitsPerWeaponStat;
use App\Stats\Damage\TotalDamageStat;
use App\Stats\Hits\TotalHitsStat;
use Tests\TestCase;

class StatTest extends TestCase
{
	private $data = [
		'damage.ak47.head' => 100,
		'damage.ak47.body' => 200,
		'damage.aug.head'  => 200,
		'damage.aug.leg'   => 20,
		'hits.aug.head'    => 2,
		'hits.aug.led'    => 1,
		'hits.ak47.head'    => 4,
		'hits.ak47.body'    => 10,
	];

	protected function bootstrap($class)
	{
		$session = factory(Session::class)->make();
		$builder = new SessionBuilder($session);
		$stat = new $class($builder);

		$stat->init($this->data);

		return $stat;
	}

	/** @test */
	public function damage_per_weapon_works()
	{
		$stat = $this->bootstrap(DamagePerWeaponStat::class);

		$this->assertEquals([
			'ak47' => 300,
			'aug'  => 220,
		], $stat->getValue());
	}

	/** @test */
	public function damage_per_hitgroup_works()
	{
		$stat = $this->bootstrap(DamagePerHitGroupStat::class);

		$this->assertEquals([
			'head' => 300,
			'body' => 200,
			'leg'  => 20,
		], $stat->getValue());
	}

	/** @test */
	public function damage_total_works()
	{
		$stat = $this->bootstrap(TotalDamageStat::class);

		$stat->init($this->data);

		$this->assertEquals(520, $stat->getValue());
	}


	/** @test */
	public function hits_per_weapon_works()
	{
		/** @var HitsPerWeaponStat $stat */
		$stat = $this->bootstrap(HitsPerWeaponStat::class);

		$this->assertEquals([
			'ak47' => 300,
			'aug'  => 220,
		], $stat->getValue());
	}

	/** @test */
	public function hits_per_hitgroup_works()
	{
		$stat = $this->bootstrap(HitsPerHitGroupStat::class);

		$this->assertEquals([
			'head' => 300,
			'body' => 200,
			'leg'  => 20,
		], $stat->getValue());
	}

	/** @test */
	public function hits_total_works()
	{
		$stat = $this->bootstrap(TotalHitsStat::class);

		$stat->init($this->data);

		$this->assertEquals(17, $stat->getValue());
	}
}

<?php
declare(strict_types=1);

namespace Tests\Unit;

use App\Services\CalculatePointService;
use PHPUnit\Framework\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CalclatePointServiceTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * 例外処理のテスト
     * @test
     * @group calcPointException
     */
    public function exception_try_catch()
    {
      try {
        throw new \InvalidArgumentException('message', 200);
        $this->fail(); // ① 例外がスローされない時はテストを失敗させる
      } catch (\Throwable $e) {
        // 指定した例外クラスがスローされているか
        $this->expectException(\InvalidArgumentException::class);
        // スローされた例外のコードを検証
        $this->expectExceptionCode(200);
        // スローされた例外のメッセージを検証
        $this->expectExceptionMessage('message');

      }
    }

    /**
     * @return array
     */
    public function dataProvider_for_calcPoint()
    {
      // [$expected, $amount]
      return [
        '購入金額が0なら0ポイント'=>[0, 0],
        '購入金額が999なら0ポイント'=>[0, 999],
        '購入金額が1000なら10ポイント'=>[10, 1000],
        '購入金額が9999なら99ポイント'=>[99, 9999],
        '購入金額が10000なら200ポイント'=>[200, 10000],
      ];
    }

    /**
     * @test
     * @group calcPointService
     * @dataProvider  dataProvider_for_calcPoint
     */
    public function calcPoint(int $expected, int $amount)
    {
      $result = CalculatePointService::calcPoint($amount);
      $this->assertSame($expected, $result);
    }

    /**
     * @test
     * @group calcPointService_funokazu
     * @expectedException App\Exceptions\PreConditionException
     * @expectedExceptionMessage 購入金額が負の数
     */
    public function calcPoint_購入金額が負の数なら例外をスロー()
    {
      CalculatePointService::calcPoint(-1);
    }

}

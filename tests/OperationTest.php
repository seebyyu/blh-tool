<?php
// +----------------------------------------------------------------------
// | Do what we can do
// +----------------------------------------------------------------------
// | Date  : 2020/5/22 - 8:44 下午
// +----------------------------------------------------------------------
// | Author: seebyyu <seebyyu@gmail.com> :)
// +----------------------------------------------------------------------
namespace blh\tests;


use Blh\Factory;
use Blh\Operation\Exceptions\BusinessException;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase
{

    /**
     * @var Factory
     */
    protected $app;

    /**
     * @var array
     */
    protected $config = [

        /**
         * 账号基本信息，联系客服发放
         */
        'app_id' => '',
        'secret' => '',

        'sms' => [
            'sign' =>  'test'
        ],

        /**
         * 配置缓存前缀
         * - 多数应用于令牌
         */
        'token' => [
            'prefix' => 'blh_tool',
        ],

        /**
         * http 客户端
         * http://docs.guzzlephp.org/en/stable/request-config.html
         */
        'http' => [
            'base_uri' => 'https://t.blhapi.li91.com',
            'verify' => false
        ],
    ];

    /**
     * init
     *
     * @return void
     */
    public function setUp()
    {
        $this->app = Factory::operation($this->config);
    }

    /**
     * Request Token
     *
     * @throws \Blh\Operation\Exceptions\BusinessException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testToken()
    {
        $this->assertIsString($this->app->token->getRefreshedToken());
    }

    /**
     * 收货地址三级
     *
     * @throws \Blh\Kernel\Exceptions\InvalidArgumentException
     * @throws \Blh\Operation\Exceptions\BusinessException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testAddress()
    {
        $this->assertIsArray($this->app->address->list());
    }

    /**
     * 运营系统消息列表
     *
     * @throws \Blh\Operation\Exceptions\BusinessException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testNotice()
    {
        $this->assertArrayHasKey('notice_num', $this->app->notice->getList());
    }

    /**
     * 发送短信
     *
     * @throws BusinessException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testSms()
    {
        $result = false;

        try {
            $result = $this->app->sms->send('1398****804', 'see hi');
        }catch (BusinessException $exception){
            $errMsg = $exception->getMessage();
        }

        $this->assertNull($result, $errMsg ?? '');
    }
}
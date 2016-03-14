<?php


namespace Twilio\Tests\Unit\Http;


use Twilio\Http\CurlClient;
use Twilio\Tests\Unit\UnitTest;

class TestCurlClientOptions extends UnitTest {

    public function testPreemptiveAuthorization() {
        $client = new CurlClient();

        $options = $client->options(
            'GET',
            'http://api.twilio.com',
            array(),
            array(),
            array(),
            'test-user',
            'test-password'
        );

        $this->assertArrayHasKey(CURLOPT_HTTPHEADER, $options);

        $headers = $options[CURLOPT_HTTPHEADER];

        $authorization = null;
        foreach ($headers as $header) {
            $parse = explode(':', $header);
            $headerKey = $parse[0];
            if ($headerKey == 'Authorization') {
                $authorization = $header;
                break;
            }
        }

        $this->assertNotNull($authorization);

        $authorizationPayload = explode(' ', $authorization);
        $encodedAuthorization = array_pop($authorizationPayload);
        $decodedAuthorization = base64_decode($encodedAuthorization);

        $this->assertEquals('test-user:test-password', $decodedAuthorization);
    }


    /**
     * @param string $message Failure Message
     * @param mixed[] $params Params with which to build the query
     * @param string $expected Expected query string
     * @dataProvider buildQueryProvider
     */
    public function testBuildQuery($message, $params, $expected) {
        $client = new CurlClient();
        $actual = $client->buildQuery($params);
        $this->assertEquals($expected, $actual, $message);
    }

    public function buildQueryProvider() {
        return array(
            array(
                'Empty Params',
                array(),
                '',
            ),
            array(
                'Single Scalar',
                array('a' => 'z'),
                'a=z',
            ),
            array(
                'Multiple Scalars',
                array(
                    'a' => 'z',
                    'b' => 'y',
                ),
                'a=z&b=y',
            ),
            array(
                'Type Coercion: Booleans',
                array(
                    'a' => true,
                    'b' => false,
                ),
                'a=1&b=',
            ),
            array(
                'Type Coercion: Integers',
                array(
                    'a' => 7,
                    'b' => -14,
                    'c' => 0,
                ),
                'a=7&b=-14&c=0',
            ),
            array(
                'Nested Arrays',
                array(
                    'a' => array(1, 2, 3),
                    'b' => array('x', 'y', 'z'),
                ),
                'a=1&a=2&a=3&b=x&b=y&b=z',
            ),
            array(
                'URL Safety',
                array(
                    'a' => 'un$afe:// value!',
                ),
                'a=un%24afe%3A%2F%2F+value%21',
            )
        );
    }

    /**
     * @param $method
     * @param $params
     * @param $expected
     * @dataProvider queryStringProvider
     * @throws \Twilio\Exceptions\EnvironmentException
     */
    public function testQueryString($method, $params, $expected) {
        $client = new CurlClient();

        $actual = $client->options($method, 'url', $params);

        $this->assertEquals($expected, $actual[CURLOPT_URL]);
    }

    public function queryStringProvider() {
        $methods = array('GET', 'POST', 'PUT', 'DELETE', 'OPTIONS', 'HEAD', 'CUSTOM');
        $cases = array();

        foreach ($methods as $method) {
            $cases[] = array(
                $method,
                array(),
                'url',
            );

            $cases[] = array(
                $method,
                array(
                    'a' => '$z',
                    'b' => 7,
                    'c' => array(1, 'x', 2),
                ),
                'url?a=%24z&b=7&c=1&c=x&c=2',
            );
        }

        return $cases;
    }

    /**
     * @param $params
     * @param $data
     * @dataProvider postFieldsProvider
     * @throws \Twilio\Exceptions\EnvironmentException
     */
    public function testPostFields($params, $data) {
        $client = new CurlClient();

        $actual = $client->options('POST', 'url', $params, $data);

        $this->assertEquals($data, $actual[CURLOPT_POSTFIELDS]);
    }

    public function postFieldsProvider() {
        return array(
            array(
                array(),
                array(),
            ),

            array(
                array(
                    'a' => 'x',
                ),
                array(
                    'a' => 'b',
                ),
            ),

            array(
                array(
                    'a' => 'x',
                ),
                array(
                    'a' => 'x',
                )
            )
        );
    }

    public function testPutFile() {
        $client = new CurlClient();
        $actual = $client->options('PUT', 'url', array(), array('a' => 1, 'b' => 2));
        $this->assertNotNull($actual[CURLOPT_INFILE]);
        $this->assertEquals('a=1&b=2', fread($actual[CURLOPT_INFILE], $actual[CURLOPT_INFILESIZE]));
        $this->assertEquals(7, $actual[CURLOPT_INFILESIZE]);
    }
}
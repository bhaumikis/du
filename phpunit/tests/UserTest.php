<?php
namespace Infostretch\Phpunit;
namespace Infostretch\GuzzleHttp;
use Guzzle\Http\Client;
use PHPUnit_Extensions_Selenium2TestCase;
use PHPUnit_Framework_TestCase;
use model\usertypesModel;

class UserTest extends PHPUnit_Framework_TestCase //PHPUnit_Extensions_Selenium2TestCase
//class UserTest extends PHPUnit_Extensions_Selenium2TestCase
{
	protected $client;
	
	public function testApiLogin() 
	{
		// create our http client (Guzzle)
		$this->client = new Client([
			'base_uri' => 'http://localhost/dujen'
		]);
		$dataSet[] = array('mobile_number' => '9033235740','password' => 'Admin123#');
		//$dataSet[] = array('mobile_number' => '9090909111','password' => 'Admin123#');
		//$dataSet[] = array('mobile_number' => '9090909090','password' => 'Admin123#');
		foreach($dataSet as $data) {
			$request = $this->client->post('http://localhost/dujen/services/login', null, ($data));
			$response = $request->send();
			$result = json_decode($response->getBody(true), true);
			$this->assertEquals(200, $response->getStatusCode());
			//print_r($result); die;
			$this->assertEquals(0, $result["response"]["#attributes"]["error"]);
		}
	}
	/*
	protected function setUp()
    {
        //$this->setHost('localhost');
        //$this->setPort(80);
        $this->setBrowser('*firefox');
        $this->setBrowserUrl('http://localhost/du/admin/index');
        
    }
	public function testLogin()
	{
		$this->setBrowser('*firefox');
		$this->open('http://localhost/du/admin/index');
		sleep(5);
		$this->type("id=email", "bhaumik.patel@infostretch.com");
		$this->type("id=password", "Admin123#");
		$this->clickAndWait("id=submit");
		$this->assertTextPresent("Welcome, Bhaumik");
		$this->assertTextPresent("Welcome");
	}
	/*
	public function testSimple1() 
	{
		$this->assertEquals(3, 1 + 2);
	}
	public function testSimple2()
	{
		$this->assertEquals(5, 1 + 2);
	}
	*/
}
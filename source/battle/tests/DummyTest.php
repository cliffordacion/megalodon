<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class DummyTest extends TestCase
{
    public function testPassAssertArrayHasKey() 
    { 
       $dummy = new App\Dummy(); 
 
       $this->assertArrayHasKey('storage', $dummy::getConfigArray()); 
    } 

    public function testFailAssertArrayHasKey() 
    { 
       $dummy = new App\Dummy(); 
 
       $this->assertArrayNotHasKey('foo', $dummy::getConfigArray()); 
    }

    public function testAssertClassHasStaticAttribute() 
    { 
      $this->assertClassHasStaticAttribute('availableLocales', 
      App\Dummy::class); 
    } 

    public function testAssertRegExp() 
    { 
       $this->assertRegExp(
            '/^CODE\-\d{2,7}[A-Z]$/', 
            App\Dummy::getRandomCode()
        ); 
    } 
 
}

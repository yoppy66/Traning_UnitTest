<?php

namespace Tests\Unit;

use App\Models\Person;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;



class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }

    public function testBasicTest()
    {
        $data = [
            'id'   => 102,
            'name' => 'DUMMY',
            'email'=> 'dummy@mail',
            'age'  => 0,
        ];
        $person = new Person();
        $person->fill($data)->save();
        $this->assertDatabaseHas('people',$data);


//        $person->name = 'NOT-DUMMY';
//        $person->save();
//        $this->assertDatabaseMissing();
    }

}

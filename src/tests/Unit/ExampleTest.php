<?php

namespace Tests\Unit;

use App\Models\Person;
//use PHPUnit\Framework\TestCase;
use Tests\TestCase;

use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;
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

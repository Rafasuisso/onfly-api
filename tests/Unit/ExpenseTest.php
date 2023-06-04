<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Expense;
use Carbon\Carbon;

class ExpenseTest extends TestCase
{
    /** @test */
    public function check_columns()
    {
        $expense = new Expense();

        $expected = [
            'description',
            'date',
            'value',
            'user_id'
        ];

        $arrayCompared = array_diff($expected, $expense->getFillable());
        $this->assertEquals(0, count($arrayCompared));
    }

    /** @test */
     public function check_columns_types()
    {
       
        $expense = new Expense();
        $expense->description = 'teste';
        $expense->value = 10;
        // $expense->date = Carbon::now();
        $expense->user_id = 1;

        $this->assertEquals('string', gettype($expense->description));
        $this->assertEquals('integer', gettype($expense->value));
        // $this->assertEquals('datetime', gettype($expense->date));
        $this->assertEquals('integer', gettype($expense->user_id));
    }
}

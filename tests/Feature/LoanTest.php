<?php

namespace Tests\Feature;

use App\Models\Loan;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * Тест создания нового займа.
     *
     * @return void
     */
    public function testCreateNewLoan()
    {
        $response = $this->post('/api/loans', [
            'name' => 'UserName',
            'second_name' => 'UserSecondName',
            'amount_debt' => 150,
        ]);

        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'UserName',
            'second_name' => 'UserSecondName',
            'amount_debt' => 150,
        ]);

        $responseData = $response->json();

        $this->assertDatabaseHas('loans', [
            'id' => $responseData['id'],
            'name' => 'UserName',
            'second_name' => 'UserSecondName',
            'amount_debt' => 150,
        ]);
    }

    /**
     * Тест получения займа
     *
     * @return void
     */
    public function testGetLoanById()
    {
        $loan = Loan::factory()->create();

        $response = $this->get('/api/loans/' . $loan->id);

        $response->assertStatus(200);
        $response->assertJson(['id' => $loan->id]);
    }

    /**
     * Тест удаления займа.
     *
     * @return void
     */
    public function testDeleteLoan()
    {
        $loan = Loan::factory()->create();

        $response = $this->delete('/api/loans/' . $loan->id);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);

        $this->assertDeleted('loans', ['id' => $loan->id]);
    }

    /**
     * Тест изменения займа.
     *
     * @return void
     */
    public function testUpdateLoan()
    {
        $loan = Loan::factory()->create();

        $response = $this->put('/api/loans/' . $loan->id, [
            'name' => 'NewUserName',
            'second_name' => 'NewUserSecondName',
            'amount_debt' => 200,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => 'NewUserName',
            'second_name' => 'NewUserSecondName',
            'amount_debt' => 200,
        ]);

        $response->assertJsonStructure([
            'id',
            'name',
            'second_name',
            'amount_debt',
            'created_at',
            'updated_at',
        ]);
    }

    /**
     * Тест получения списка займов.
     *
     * @return void
     */
    public function testGetLoanNoFilter()
    {
        $loans = Loan::factory()->count(5)->create();

        $response = $this->get('/api/loans');

        $response->assertStatus(200);
        foreach ($loans as $loan) {
            $response->assertJsonFragment([
                'created_at' => $loan->created_at,
                'id' => $loan->id,
            ]);
        }
    }

    /**
     * Тест получения списка займов с фильтрацией по amount_debt_upper.
     *
     * @return void
     */
    public function testGetLoanByParameterAmountDebtUpper()
    {
        $amount_debts = [100, 200, 300, 400, 500];

        foreach ($amount_debts as $amount_debt) {
            Loan::factory()->create(['amount_debt' => $amount_debt]);
        }

        $response = $this->get('/api/loans?amount_debt_upper=' . $amount_debts[2]);

        $response->assertStatus(200);

        $loans = $response->json();

        foreach ($loans as $loan) {
            $this->assertTrue($loan['amount_debt'] <= $amount_debts[2]);
        }
    }


    /**
     * Тест получения списка займов с фильтрацией по amount_debt_lower.
     *
     * @return void
     */
    public function testGetLoanByParameterAmountDebtLower()
    {
        $amount_debts = [100, 200, 300, 400, 500];

        foreach ($amount_debts as $amount_debt) {
            Loan::factory()->create(['amount_debt' => $amount_debt]);
        }

        $response = $this->get('/api/loans?amount_debt_lower=' . $amount_debts[2]);

        $response->assertStatus(200);

        $loans = $response->json();

        foreach ($loans as $loan) {
            $this->assertTrue($loan['amount_debt'] >= $amount_debts[2]);
        }
    }

    /**
     * Тест получения списка займов с фильтрацией по amount_debt_lower и amount_debt_upper.
     *
     * @return void
     */
    public function testGetLoanByParameterAmountDebtUpperAndLower()
    {
        $amount_debts = [100, 200, 300, 400, 500];

        foreach ($amount_debts as $amount_debt) {
            Loan::factory()->create(['amount_debt' => $amount_debt]);
        }

        $response = $this->get('/api/loans?amount_debt_lower=' . $amount_debts[1] . '&amount_debt_upper=' . $amount_debts[3]);

        $response->assertStatus(200);

        $loans = $response->json();

        foreach ($loans as $loan) {
            $this->assertTrue($loan['amount_debt'] >= $amount_debts[1]);
            $this->assertTrue($loan['amount_debt'] <= $amount_debts[3]);
        }
    }

    /**
     * Тест получения списка займов с фильтрацией по date_from.
     *
     * @return void
     */
    public function testGetLoanByParameterDateFrom()
    {
        $created_dates = ['2000-01-01T00:00:00.000000Z' , '2000-01-02T00:00:00.000000Z' , '2000-01-03T00:00:00.000000Z', '2000-01-04T00:00:00.000000Z' , '2000-01-05T00:00:00.000000Z'];

        foreach ($created_dates as $created_date) {
            Loan::factory()->create(['created_at' => $created_date]);
        }

        $response = $this->get('/api/loans?date_from=' . $created_dates[2]);

        $response->assertStatus(200);

        $loans = $response->json();

        foreach ($loans as $loan) {
            $this->assertTrue($loan['created_at'] >= $created_dates[2]);
        }
    }

    /**
     * Тест получения списка займов с фильтрацией по date_to.
     *
     * @return void
     */
    public function testGetLoanByParameterDateTo()
    {
        $created_dates = ['2000-01-01T00:00:00.000000Z' , '2000-01-02T00:00:00.000000Z' , '2000-01-03T00:00:00.000000Z', '2000-01-04T00:00:00.000000Z' , '2000-01-05T00:00:00.000000Z'];

        foreach ($created_dates as $created_date) {
            Loan::factory()->create(['created_at' => $created_date]);
        }

        $response = $this->get('/api/loans?date_to=' . $created_dates[2]);

        $response->assertStatus(200);

        $loans = $response->json();

        foreach ($loans as $loan) {
            $this->assertTrue($loan['created_at'] <= $created_dates[2]);
        }
    }

    /**
     * Тест получения списка займов с фильтрацией по date_from и date_to.
     *
     * @return void
     */
    public function testGetLoanByParameterDateFromAndTo()
    {
        $created_dates = ['2000-01-01T00:00:00.000000Z' , '2000-01-02T00:00:00.000000Z' , '2000-01-03T00:00:00.000000Z', '2000-01-04T00:00:00.000000Z' , '2000-01-05T00:00:00.000000Z'];

        foreach ($created_dates as $created_date) {
            Loan::factory()->create(['created_at' => $created_date]);
        }

        $response = $this->get('/api/loans?date_from=' . $created_dates[1] . '&date_to=' . $created_dates[3]);

        $response->assertStatus(200);

        $loans = $response->json();

        foreach ($loans as $loan) {
            $this->assertTrue($loan['created_at'] >= $created_dates[1]);
            $this->assertTrue($loan['created_at'] <= $created_dates[3]);
        }
    }
}

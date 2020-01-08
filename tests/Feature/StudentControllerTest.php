<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Student;
use App\Admin;

class StudentControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = factory(Admin::class)->create();
    }

    public function testCreateStudentWithoutNecessaryInformation()
    {
        $response = $this->withHeaders([
            'token' => $this->admin->token,
            ])
            ->json(
                'POST',
                '/api/student',
                []
            );

        $response->assertStatus(412);
        $response->assertJsonFragment(['empty_arguments']);
    }

    public function testCannotCreateSameStudentTwice()
    {
        $student = factory(Student::class)->create();
        $response = $this->withHeaders([
            'token' => $this->admin->token,
        ])
        ->json(
            'POST',
            '/api/student',
            [
                'jkc_registry' => $student->jkc_registry,
                'fbk_registry' => $student->fbk_registry,
                'cbk_registry' => $student->cbk_registry,
                'student_rg' => $student->student_rg,
                'student_cpf' => $student->student_cpf,
                'name' => $student->name,
                'city_of_birth' => $student->city_of_birth,
                'state_of_birth'=> $student->state_of_birth,
                'phone' => $student->phone,
            ]
        );

        $student->forceDelete();

        $response->assertStatus(409);
        $response->assertJsonFragment([
            'Já existe aluno com esta inscrição JKC cadastrado no sistema.'
        ]);
    }

    public function testCanCreateNewStudent()
    {
        $student = factory(Student::class)->make();
        $response = $this->withHeaders([
            'token' => $this->admin->token,
        ])
        ->json(
            'POST',
            '/api/student',
            [
                'jkc_registry' => $student->jkc_registry,
                'fbk_registry' => $student->fbk_registry,
                'cbk_registry' => $student->cbk_registry,
                'student_rg' => $student->student_rg,
                'student_cpf' => $student->student_cpf,
                'name' => $student->name,
                'city_of_birth' => $student->city_of_birth,
                'state_of_birth'=> $student->state_of_birth,
                'phone' => $student->phone,
            ]
        );

        $db_student = Student::where('jkc_registry', $student->jkc_registry);
        $db_student->forceDelete();

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'message'=> 'Aluno cadastrado com sucesso.',
            'name' => $student->name,
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->admin->forceDelete();
    }
}

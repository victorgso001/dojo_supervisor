<?php

namespace App\Http\Controllers;

use App\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $skip = !$request->skip ? 0 : (int) $request->skip;
        $take = $request->take ? (int) $request->take : 20;
        $name = $request->name ? $request->name : '';
        $active = !$request->active ? 0 : (int) $request->active;
        $status = !$request->status ? 0 : $request->status;

        $students = Student::where('active', $active);

        $count = $students->count();

        if (!empty($name)) {
            $students = $students->where('name', 'like', "%$name%");
        }

        if (!empty($status)) {
            $students = $students->where('status', $status);
        }


        $students = $students->skip($skip)
            ->take($take)
            ->get(['name', 'status']);

        if (empty($students)) {
            return response(
                [
                    'error_info' => 'empty_list',
                    'message' => 'Não existem alunos cadastrados'
                ], 412
            );
        }

        return response(
            [
                'students' => $students,
                'count' => $count,
                'pages' => $count == 0 ? 0 : ceil($count/$take),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'jkc_registry' => 'required',
                'fbk_registry' => 'required',
                'cbk_registry' => 'required',
                'student_rg' => 'required',
                'student_cpf' => 'required',
                'name' => 'required',
                'city_of_birth' => 'required',
                'state_of_birth'=> 'required',
                'phone' => 'required',
            ],
            $messages = [
                'required' => 'O campo :attribute é obrigatório.',
            ]
        );

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response([
                'error_info' => 'empty_arguments',
                'message' => $errors->first(),
            ], 412);
        }

        $insc_jkc = $request->jkc_registry;

        $student = Student::where('jkc_registry', $insc_jkc)->first();

        if (!empty($student)) {
            return response([
                'error_info' => 'registry_already_exists',
                'message' => 'Já existe aluno com esta inscrição JKC cadastrado no sistema.',
            ], 409);
        }

        $student = new Student;

        $student->jkc_registry = $request->jkc_registry;
        $student->fbk_registry = $request->fbk_registry;
        $student->cbk_registry = $request->cbk_registry;
        $student->student_rg = $request->student_rg;
        $student->student_cpf = $request->student_cpf;
        $student->name = $request->name;
        $student->inscription_date = $request->inscription_date ? $request->inscription_date : null;
        $student->birthday = !empty($request->birthday) ? $request->birthday : null;
        $student->father_name = !empty($request->father_name) ? $request->father_name : null;
        $student->mother_name = !empty($request->mother_name) ? $request->mother_name : null;
        $student->city_of_birth = $request->city_of_birth;
        $student->state_of_birth= $request->state_of_birth;
        $student->phone = $request->phone;
        $student->address_street = !empty($request->address_street) ? $request->address_street : null;
        $student->address_number = !empty($request->address_number) ? $request->address_number : null;
        $student->address_state = !empty($request->address_state) ? $request->address_state: null;
        $student->address_city = !empty($request->address_city) ? $request->address_city : null;
        $student->active = 1;
        $student->status = 1;

        $student->save();

        return response([
            'message' => 'Aluno cadastrado com sucesso.',
            'name' => $student->name,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $input = $request->all();

        $student->update($input);

        return response([
            'message' => 'Dados do aluno atualizados com sucesso',
            'student' => $student->id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }
}

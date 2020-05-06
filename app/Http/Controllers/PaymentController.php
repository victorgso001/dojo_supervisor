<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Payment;
use App\Student;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->student_id;
        $skip = (int) $request->skip > 0 ? (int) $request->skip : 0;
        $take = (int) $request->take > 0 ? (int) $request->take : 0;

        if (empty($id)) {
            return response([
                'error_info' => 'empty_student',
                'message' => 'Não foi informado id de aluno para busca de mensalidades',
            ], 404);
        }

        $payments = Payment::where('student_id', $id)
            ->skip($skip)
            ->take($take)
            ->get([
                'id',
                'due_date',
                'value',
                'payed_value',
                'past_due',
            ]);

        if (empty($payments)) {
            return response([
                'error_info' => 'payments_not_found',
                'message' => 'Não existem mensalidades cadastradas para o aluno',
            ], 404);
        }

        $count = $payments->count();

        return response([
            'student' => $id,
            'payments' => $payments,
            'count' => $count,
            'page' => $count == 0 ? 0 : ceil($count/$take),
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $student_id = $request->student_id;
        $due_date = Carbon::createFromFormat('d/m/Y', $request->due_date)
            ->format('Y-m-d');
        $value = floatval($request->value);
        $payed_value = floatval($request->payed_value) > 0 ? floatval($request->payed_value) : 0;
        $today = Carbon::now()->format('Y-m-d');
        $past_due = 0;

        if ($due_date < $today) {
            $past_due = 1;
        }

        $payment = new Payment;
        $payment->student_id = $student_id;
        $payment->due_date = $due_date;
        $payment->value = $value;
        $payment->payed_value = $payed_value;
        $payment->past_due = $past_due;

        if (!$payment->save()) {
            return response([
                'error_info' => 'payment_save_error',
                'message' => 'Erro ao registrar a mensalidade no sistema. Tente mais tarde.',
            ], 417);
        }

        return response([
            'message' => 'Mensalidade registrada com sucesso',
            'student' => $student_id,
            'payment' => $payment->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        $today = Carbon::now()->format('Y-m-d');

        if ($today > $payment->due_date && $payment->past_due == 0) {
            $payment->past_due = 1;
            $payment->save();
        } elseif ($today <= $payment->due_date && $payment->past_due == 1) {
            $payment->past_due = 0;
            $payment->save();
        }

        $payment = $payment->get([
            'id',
            'due_date',
            'value',
            'payed_value',
            'past_due',
        ]);

        return response([
            'payment' => $payment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $input = $request->all();

        $input['due_date'] = Carbon::createFromFormat('d/m/Y', $input['due_date'])
            ->format('Y-m-d');

        $payment->update($input);

        return response([
            'message' => 'Mensalidade atualizada com sucesso',
            'payment' => $payment->id,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        if (!$payment->trashed()) {
            return response([
                'error_info' => 'cannot_delete_payment',
                'message' => 'Ocorreu um erro ao tentar excluir a mensalidade. Tente novamente mais tarde',
            ], 401);
        }

        return response([
            'message' => 'Pagamento excluído com sucesso.',
            'id' => $payment->id,
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Expense;
use Validator;
use App\Http\Resources\ExpenseResource;
use Illuminate\Support\Facades\Auth;


class ExpenseController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expense::all();
    
        return $this->sendResponse(ExpenseResource::collection($expenses), 'Expenses retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'description' => 'required',
            'value' => 'required',
            'date' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        $userId = Auth::id();
        $input['user_id'] = $userId;
        // dd($input);

        $expense = Expense::create($input);
   
        return $this->sendResponse(new ExpenseResource($expense), 'Expense created successfully.');
    } 
   
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expense = Expense::find($id);
  
        if (is_null($expense)) {
            return $this->sendError('Expense not found.');
        }
   
        return $this->sendResponse(new ExpenseResource($expense), 'Expense retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'description' => 'required',
            'value' => 'required',
            'date' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $expense->description = $input['description'];
        $expense->value = $input['value'];
        $expense->date = $input['date'];
        $expense->save();
   
        return $this->sendResponse(new ExpenseResource($expense), 'Expense updated successfully.');
    }
   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
   
        return $this->sendResponse([], 'Expense deleted successfully.');
    }
}

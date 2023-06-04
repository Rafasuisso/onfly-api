<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\Expense;
use Validator;
use App\Http\Resources\ExpenseResource;
use App\Http\Requests\ExpenseRequest;
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
        $userId = Auth::id();
        $expenses = Expense::all()->where('user_id', $userId);
    
        return $this->sendResponse(ExpenseResource::collection($expenses), 'Expenses retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpenseRequest $request)
    {
        $input = $request->all();
   
        $userId = Auth::id();
        $input['user_id'] = $userId;

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
        $userId = Auth::id();
        $expense = Expense::find($id)->where('user_id', $userId);
  
        if (is_null($expense)) {
            return $this->sendError('Expense not found.');
        }
   
        return $this->sendResponse(new ExpenseResource($expense), 'Expense retrieved successfully.');
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ExpenseRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpenseRequest $request, Expense $expense)
    {
        $this->authorize('updateAndDelete', $expense);
        
        $input = $request->all();
        
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
        $this->authorize('updateAndDelete', $expense);
        $expense->delete();
   
        return $this->sendResponse([], 'Expense deleted successfully.');
    }
}

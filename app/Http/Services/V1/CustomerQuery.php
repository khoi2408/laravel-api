<?php

namespace App\Http\Services\V1;

use Illuminate\Http\Request;

class CustomerQuery {
    protected $safeParms = [
        // eq = equal, gt = greater than, lt = less than
        'name' => ['eq'],
        'type' => ['eq'],
        'email' => ['eq'],
        'address' => ['eq'],
        'city' => ['eq'],
        'state' => ['eq'],
        'postalCode' => ['eq', 'gt', 'lt'], 
    ];

    protected $columnMap = [
        'postalCode' => 'postal_code',
    ];

    protected $operatorMap = [
        'eq' => '=',
        'lt' => '<',
        'lte' => '<=',
        'gt' => '>',
        'gte' => '>=',
    ];

    public function transform(Request $request) {
        $eloQuery = [];

        foreach($this->safeParms as $parm => $operators) {
            $query = $request->query($parm); // get the query string

            if(!isset($query)) { // if $parm is not in $request, then skip
                continue;
            }
            $column = $this->columnMap[$parm] ?? $parm; // if $parm is not in $columnMap, then use $parm
            foreach($operators as $operator) { // loop through the operators
                if(isset($query[$operator])) { // if the operator is in the query string
                    $eloQuery[] = [$column, $this->operatorMap[$operator], $query[$operator]]; // add to eloQuery
                }
            }
        }

        return $eloQuery;
    }
}
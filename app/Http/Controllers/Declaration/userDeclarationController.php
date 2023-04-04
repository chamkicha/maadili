<?php

namespace App\Http\Controllers\Declaration;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class userDeclarationController extends Controller
{
    public function declarationSubmission(Request $request): JsonResponse|array
    {

        $validator = Validator::make($request->all(), [
            'declaration_type' => 'required|integer',
            'date_of_birth' => 'required',
            'marital_status' => 'required',
            'nationality' => 'required',
            'box' => 'required',
            'ward' => 'required|integer',
            'phone_number' => 'required',
            'title' => 'required|integer',
            'employment_date' => 'required',
            'employer_name' => 'required|integer',
            'employer_type' => 'required|integer',
            'salary_per_year' => 'required|string',
            'allowance_per_year' => 'required|string',
            'revenue_per_year' => 'required|string',
            'former_employment_from' => 'required',
            'former_employment_to' => 'required',
            'former_employer_name' => 'required|integer',
            'cash_in_hand' => 'required',
            'bank_information' => 'required|array',
            'bank_information.*.bank_name' => 'required|string',
            'bank_information.*.account_number' => 'required|string',
            'bank_information.*.amount' => 'required|string',
            'bank_information.*.source' => 'required|integer',
            'bank_information.*.profit' => 'required',
            'bank_information.*.usage' => 'required',
            'bank_information.*.is_local' => 'required',
            'bank_information.*.family_member' => 'integer',
            'share_and_dividend' => 'required|array',
            'share_and_dividend.*.share_amount' => 'required|string',
            'share_and_dividend.*.institute_name' => 'required|string',
            'share_and_dividend.*.family_member' => 'integer',
            'share_and_dividend.*.dividend_amount' => 'required|string',
            'buildings' => 'required|array',
            'buildings.*.family_member' => 'required|integer',
            'buildings.*.building_type' => 'required|integer',
            'buildings.*.country' => 'required|integer',
            'buildings.*.region' => 'required|integer',
            'buildings.*.ward' => 'required|integer',
            'buildings.*.street' => 'required|string',
            'buildings.*.area_size' => 'required|string',
            'buildings.*.value_or_costs_of_construction_or_purchase' => 'required|string',
            'buildings.*.source_of_income' => 'required|integer',
            'buildings.*.usage' => 'required|integer',
            'properties' => 'required|array',
            'properties.*.family_member' => 'required|integer',
            'properties.*.size_of_the_area' => 'required|string',
            'properties.*.value_or_costs_of_construction_or_purchase' => 'required|string',
            'properties.*.source_of_income' => 'required|integer',
            'properties.*.country' => 'required|integer',
            'properties.*.region' => 'required|integer',
            'properties.*.ward' => 'required|integer',
            'properties.*.usage' => 'required|integer',
            'properties.*.street' => 'required|string',
            'transportations' => 'required|array',
            'transportations.*.family_member' => 'required|integer',
            'transportations.*.transportation_type' => 'required|integer',
            'transportations.*.transport_number' => 'required|string',
            'transportations.*.cost' => 'required|float',
            'transportations.*.source_of_income' => 'required|integer',
            'transportations.*.country' => 'required|integer',
            'transportations.*.region' => 'required|integer',
            'transportations.*.ward' => 'required|integer',
            'transportations.*.street' => 'required|string',
            'transportations.*.usage' => 'required|integer',
            'debts' => 'required|array',
            'debts.*.family_member' => 'required|integer',
            'debts.*.debt_type' => 'required|integer',
            'debts.*.institute' => 'required|string',
            'debts.*.amount' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        return $request->all();
    }
}

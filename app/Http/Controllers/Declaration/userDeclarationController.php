<?php

namespace App\Http\Controllers\Declaration;

use App\Http\Controllers\Controller;
use App\Models\Financial_year;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
            'former_title' => 'required|integer',
            'former_employer_type' => 'required|integer',
            'former_salary_per_year' => 'required|string',
            'former_allowance_per_year' => 'required|string',
            'former_revenue_per_year' => 'required|string',
            'cash_in_hand' => 'required|array',
            'cash_in_hand.*.family_member' => 'integer',
            'cash_in_hand.*.cash' => 'required|string',
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
            'share_and_dividend.*.amount_of_stock' => 'required|string',
            'share_and_dividend.*.institute_name' => 'required|string',
            'share_and_dividend.*.family_member' => 'integer',
            'share_and_dividend.*.country' => 'integer',
            'share_and_dividend.*.region' => 'integer',
            'share_and_dividend.*.district' => 'integer',
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
            'transportations.*.cost' => 'required|string',
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

        $user = User::find(auth()->user()->id);
        $user->date_of_birth = $request->input('date_of_birth');
        $user->email = $request->input('email');
        $user->nationality = $request->input('nationality');
        $user->po_box = $request->input('po_box');
        $user->village = $request->input('village');
        $user->ward_id = $request->input('ward');
        $user->marital_status_id = $request->input('marital_status');
        $user->sex_id = $request->input('sex');
        $user->save();

        $year = Financial_year::where('is_active','=',1)->first();

        $declaration_data = [
            'secure_token' => Str::uuid(),
            'declaration_type_id' => $request->input('declaration_type'),
            'financial_year_id' => $year->id,
            'flag' => 'saved',
        ];

        $declaration = $user->declarations()->updateOrCreate($declaration_data);

        $employment_data = [
            'secure_token' => Str::uuid(),
            'title_id' => $request->input('title'),
            'office_id' => $request->input('employer_name'),
            'employment_type_id' => $request->input('employer_type'),
            'salary_per_year' => $request->input('salary_per_year'),
            'allowance_per_year' => $request->input('allowance_per_year'),
            'income_from_other_source_per_year' => $request->input('revenue_per_year'),
            'from' => $request->input('employment_date')
        ];

        $declaration->employments()->updateOrCreate($employment_data);

        $former_employment_data = [
            'secure_token' => Str::uuid(),
            'title_id' => $request->input('former_title'),
            'office_id' => $request->input('former_employer_name'),
            'employment_type_id' => $request->input('former_employer_type'),
            'salary_per_year' => $request->input('former_salary_per_year'),
            'allowance_per_year' => $request->input('former_allowance_per_year'),
            'income_from_other_source_per_year' => $request->input('former_revenue_per_year'),
            'from' => $request->input('former_employment_from'),
            'to' => $request->input('former_employment_to'),
            'is_current' => 0,
        ];

        $declaration->employments()->updateOrCreate($former_employment_data);

        if (count($request->input('cash_in_hand')) > 0 ){
            foreach ($request->input('cash_in_hand') as $cash){
                $cash_data = [
                    'secure_token' => Str::uuid(),
                    'family_member_id' => $cash['family_member'],
                    'cash' => $cash['cash']
                ];

                $declaration->cashes()->updateOrCreate($cash_data);
            }
        }

        if (count($request->input('bank_information')) > 0 ){

            foreach ($request->input('bank_information') as $bank){
                $bank_data = [
                    'secure_token' => Str::uuid(),
                    'family_member_id' => $bank['family_member'],
                    'institute_name' => $bank['bank_name'],
                    'account_number' => $bank['account_number'],
                    'amount' => $bank['amount'],
                    'source_of_income_id' => $bank['source'],
                    'is_local' => $bank['is_local'],
                    'profit' => $bank['profit'],
                    'type_of_use_id' => $bank['usage'],
                ];

                $declaration->banks()->updateOrCreate($bank_data);
            }

        }

        if (count($request->input('share_and_dividend')) > 0){

            foreach ($request->input('share_and_dividend') as $share){

                $share_data = [
                    'secure_token' => Str::uuid(),
                    'family_member_id' => $share['family_member'],
                    'amount_of_stock' => $share['amount_of_stock'],
                    'institute_name' => $share['institute_name'],
                    'country_id' => $share['country'],
                    'region_id' => $share['region'],
                    'district_id' => $share['district'],
                    'amount_of_dividend' => $share['dividend_amount']
                ];

                $declaration->share_and_dividends()->updateOrCreate($share_data);
            }
        }

        if (count($request->input('buildings')) > 0){

            foreach ($request->input('buildings') as $building){

                $building_data = [
                    'secure_token' => Str::uuid(),
                    'family_member_id' => $building['family_member'],
                    'building_type_id' => $building['building_type'],
                    'country_id' => $building['country'],
                    'region_id' => $building['region'],
                    'ward_id' => $building['ward'],
                    'street' => $building['street'],
                    'area_size' => $building['area_size'],
                    'value_or_costs_of_construction_or_purchase' => $building['value_or_costs_of_construction_or_purchase'],
                    'source_of_income_id' => $building['source_of_income'],
                    'type_of_use_id' => $building['usage'],
                ];

                $declaration->house_and_buildings()->updateOrCreate($building_data);
            }

        }

        if (count($request->input('properties')) > 0){
            foreach ($request->input('properties') as $properties){
                $properties_data = [
                    'secure_token' => Str::uuid(),
                    'family_member_id' => $properties['family_member'],
                    'size_of_the_area' => $properties['size_of_the_area'],
                    'value_or_costs_of_construction_or_purchase' => $properties['value_or_costs_of_construction_or_purchase'],
                    'source_of_income_id' => $properties['source_of_income'],
                    'country_id' => $properties['country'],
                    'region_id' => $properties['region'],
                    'ward_id' => $properties['ward'],
                    'type_of_use_id' => $properties['usage'],
                    'street' => $properties['street'],
                ];

                $declaration->properties()->updateOrCreate($properties_data);
            }

        }

        if (count($request->input('transportations')) > 0){

            foreach ($request->input('transportations') as $transportation){
                $transportation_data = [
                    'secure_token' => Str::uuid(),
                    'family_member_id' => $transportation['family_member'],
                    'transportation_type_id' => $transportation['transportation_type'],
                    'transport_number' => $transportation['transport_number'],
                    'cost' => $transportation['cost'],
                    'source_of_income_id' => $transportation['source_of_income'],
                    'country_id' => $transportation['country'],
                    'region_id' => $transportation['region'],
                    'ward_id' => $transportation['ward'],
                    'street' => $transportation['street'],
                    'type_of_use_id' => $transportation['usage'],
                ];

                $declaration->transportations()->updateOrCreate($transportation_data);
            }
        }

        if (count($request->input('debts')) > 0){

            foreach ($request->input('debts') as $debts){
                $debt_data = [
                    'secure_token' => Str::uuid(),
                    'family_member_id' => $debts['family_member'],
                    'debt_type_id' => $debts['debt_type'],
                    'institute' => $debts['institute'],
                    'amount' => $debts['amount'],
                ];

                $declaration->debts()->updateOrCreate($debt_data);
            }

        }

        $response = ['statusCode' => 200, 'message' => 'Tamko lako limetumwa kikamilifu'];

        return response()->json($response);


    }
}

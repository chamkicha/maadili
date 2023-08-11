<?php

use App\Models\Menu_lookup;
use App\Models\User_declaration;
use App\Models\Declaration_type;
use App\Models\Section;

if (!function_exists('createMenuLookup')) {
    function createMenuLookup($stage)
    {

        $menu_lookup = Menu_lookup::where('user_id','=',auth()->user()->id)->first();

        if($menu_lookup){
            $menu_lookup->{$stage} = true;
            $menu_lookup->save();
        }else{

        $data = [];
        $data['user_id']=auth()->user()->id;
        $data[$stage]= true;
        $announcement = Menu_lookup::create($data);

        }

    }

    
}

if (!function_exists('sectioncount')) {
    function sectioncount($user_declaration_id,$user_id,$is_pl)
    {

       $user_declaration = User_declaration::where('id', $user_declaration_id)->first();

        if($user_declaration)
        {

            $sections = Declaration_type::join('declaration_sections','declaration_sections.declaration_type_id','=','declaration_types.id')
                                            ->join('sections','sections.id','=','declaration_sections.section_id')
                                            ->where('declaration_types.id',$user_declaration->declaration_type_id)
                                            ->get();
            $count_section_data = [];

            foreach ($sections as $section) {
                $table_name = strtolower($section->table_name);

                $section_datas = DB::table($table_name)
                    ->where('user_declaration_id', $user_declaration->id)
                    ->where('member_id', $user_id)
                    ->where('is_pl', $is_pl)
                    ->get();
                
                    foreach ($section_datas as $data) {
                        $count_section_data[] = 1;
                    }
            }

            $total_data_count = count($count_section_data);
            if($total_data_count){
                return $total_data_count;

            }else{
                return 0;
            }

            
        }

    }

    
}
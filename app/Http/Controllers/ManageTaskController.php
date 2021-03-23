<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ManageTask;
use App\Models\Package;
use App\Models\StatusManageTask;
use App\Models\SettingSystem;

class ManageTaskController extends Controller {

    public function index() {
        $data['setting_system'] = SettingSystem::find(1);
        $data['packages'] = Package::get();
        $data['status_manage_tasks'] = StatusManageTask::get();
        return view('manage_task', $data);
    }

    public function create() {
        // ฟังก์ชั่นนี้เอาไว้เรียก view หน้า add
    }

    public function store(Request $request) {

        $input_all = $request->all();

        if (isset($input_all['pade_date'])) {
            $input_all['pade_date'] = date('Y-m-d', strtotime($input_all['pade_date']));
        }

        $input_all['created_at'] = date('Y-m-d H:i:s');
        $input_all['created_by'] = \Auth::user()->id;
        $input_all['updated_at'] = date('Y-m-d H:i:s');
        $input_all['updated_by'] = \Auth::user()->id;

        $validator = Validator::make($request->all(), [
                    'pade_name' => 'required',
        ]);

        $return['title'] = 'เพิ่มข้อมูล';

        if ($validator->fails()) {
            $return['status'] = 0;
            $return['content'] = $validator->errors()->first();
            return $return;
        }

        \DB::beginTransaction();
        try {
            ManageTask::insert($input_all);
            \DB::commit();
            $return['status'] = 1;
            $return['content'] = 'สำเร็จ';
        } catch (Exception $e) {
            \DB::rollBack();
            $return['status'] = 0;
            $return['content'] = 'ไม่สำเร็จ' . $e->getMessage();
        }
        return $return;
    }

    public function get_by_id($id) {
        $result['manage_task'] = ManageTask::find($id);
        $result['pade_date'] = date('d-m-Y', strtotime($result['manage_task']->pade_date));
        return json_encode($result);
    }

    public function update(Request $request, $id) {

        $input_all = $request->all();

        if (isset($input_all['pade_date'])) {
            $input_all['pade_date'] = date('Y-m-d', strtotime($input_all['pade_date']));
        }

        $input_all['updated_at'] = date('Y-m-d H:i:s');
        $input_all['updated_by'] = \Auth::user()->id;

        $validator = Validator::make($request->all(), [
                    'pade_name' => 'required',
        ]);

        $return['title'] = 'แก้ไขข้อมูล';

        if ($validator->fails()) {
            $return['status'] = 0;
            $return['content'] = $validator->errors()->first();
            return $return;
        }

        \DB::beginTransaction();
        try {

            ManageTask::where('id', $id)->update($input_all);

            \DB::commit();
            $return['status'] = 1;
            $return['content'] = 'สำเร็จ';
        } catch (Exception $e) {
            \DB::rollBack();
            $return['status'] = 0;
            $return['content'] = 'ติด Validation : ' . json_encode($validator->failed());
        }
        return $return;
    }

    public function destroy($id) {
        $return['title'] = 'ลบข้อมูล';
        \DB::beginTransaction();
        try {
            ManageTask::where('id', $id)->delete();
            \DB::commit();
            $return['status'] = 1;
            $return['content'] = 'สำเร็จ';
        } catch (Exception $e) {
            \DB::rollBack();
            $return['status'] = 0;
            $return['content'] = 'ไม่สำเร็จ' . $e->getMessage();
        }
        return $return;
    }

    public function get_datatable() {
        $result = ManageTask::select();
        return \DataTables::of($result)
                        ->addIndexColumn()
                        ->editColumn('pade_date', function($rec) {
                            return date('d-m-Y', strtotime($rec->pade_date));
                        })
                        ->editColumn('price', function($rec) {
                            return number_format($rec->price, 2);
                        })
                        ->editColumn('link_page', function($rec) {
                            return '<a href="' . $rec->link_page . '" target="_blank">' . $rec->link_page . '</a>';
                        })
                        ->addColumn('action', function($rec) {
                            $str = '
                          <button type="button" class="btn btn-edit btn-warning" data-id="' . $rec->id . '">แก้ไข</button>
                          <button type="button" class="btn btn-delete btn-danger" data-id="' . $rec->id . '" data-name="' . $rec->name . '">ลบ</button>   
                            ';
                            return $str;
                        })->rawColumns(['action', 'link_page'])->make(true);
    }

}
<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CustomerGoogleAds;

class CustomerGoogleAdsController extends Controller {

    public function index() {
        if (\Gate::allows('isCEO') || \Gate::allows('isAdmin')) {
            return view('customer_google_ads');
        } else {
            abort(503);
        }
    }

    public function create() {
        // ฟังก์ชั่นนี้เอาไว้เรียก view หน้า add
    }

    public function store(Request $request) {

        $input_all = $request->all();

        if (isset($input_all['service_end_date'])) {
            $input_all['service_end_date'] = date('Y-m-d', strtotime($input_all['service_end_date']));
        }

        if (isset($input_all['expired_web_date'])) {
            $input_all['expired_web_date'] = date('Y-m-d', strtotime($input_all['expired_web_date']));
        }

        $input_all['created_at'] = date('Y-m-d H:i:s');
        $input_all['created_by'] = \Auth::user()->id;
        $input_all['updated_at'] = date('Y-m-d H:i:s');
        $input_all['updated_by'] = \Auth::user()->id;

        $validator = Validator::make($request->all(), [
                    'name' => 'required',
        ]);

        $return['title'] = 'เพิ่มข้อมูล';

        if ($validator->fails()) {
            $return['status'] = 0;
            $return['content'] = $validator->errors()->first();
            return $return;
        }

        \DB::beginTransaction();
        try {
            CustomerGoogleAds::insert($input_all);
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
        $result['customer_google_ads'] = CustomerGoogleAds::find($id);
        if ($result['customer_google_ads']->service_end_date) {
            $result['service_end_date'] = date('d-m-Y', strtotime($result['customer_google_ads']->service_end_date));
        } else {
            $result['service_end_date'] = '';
        }
        if ($result['customer_google_ads']->expired_web_date) {
            $result['expired_web_date'] = date('d-m-Y', strtotime($result['customer_google_ads']->expired_web_date));
        } else {
            $result['expired_web_date'] = '';
        }
        return json_encode($result);
    }

    public function update(Request $request, $id) {

        $input_all = $request->all();

        if (isset($input_all['service_end_date'])) {
            $input_all['service_end_date'] = date('Y-m-d', strtotime($input_all['service_end_date']));
        }

        if (isset($input_all['expired_web_date'])) {
            $input_all['expired_web_date'] = date('Y-m-d', strtotime($input_all['expired_web_date']));
        }

        $input_all['updated_at'] = date('Y-m-d H:i:s');
        $input_all['updated_by'] = \Auth::user()->id;

        $validator = Validator::make($request->all(), [
                    'name' => 'required',
        ]);

        $return['title'] = 'แก้ไขข้อมูล';

        if ($validator->fails()) {
            $return['status'] = 0;
            $return['content'] = $validator->errors()->first();
            return $return;
        }

        \DB::beginTransaction();
        try {

            CustomerGoogleAds::where('id', $id)->update($input_all);

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
            CustomerGoogleAds::where('id', $id)->delete();
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
        $result = CustomerGoogleAds::where('status', '!=', 'ไม่ต่อบริการ')->select();
        return \DataTables::of($result)
                        ->addIndexColumn()
                        ->editColumn('service_end_date', function($rec) {
                            if ($rec->service_end_date != null) {
                                if (date('Y-m-d H:i:s', strtotime("-3 day", strtotime($rec->service_end_date))) < date('Y-m-d H:i:s')) {
                                    return '<span style="padding: 3px;
    3px: ;
    border-radius: 4px;
    border: 2px solid #e3e3e3;
    box-shadow: 4px 4px 5px #c3c0b8;">' . DateThai($rec->service_end_date) . '</span>';
                                } else {
                                    return DateThai($rec->service_end_date);
                                }
                            } else {
                                return '';
                            }
                        })
                        ->editColumn('expired_web_date', function($rec) {
                            if ($rec->expired_web_date != null) {
                                return DateThai($rec->expired_web_date);
                            } else {
                                return '';
                            }
                        })
                        ->editColumn('price', function($rec) {
                            return number_format($rec->price);
                        })
                        ->editColumn('manage_ads', function($rec) {
                            return '<button type="button" class="btn btn-info"><a style="color:white;" href="' . $rec->manage_ads . '" target="_blank">ดูโฆษณา</a></button>';
                        })
                        ->editColumn('link_web', function($rec) {
                            return '<a href="' . $rec->link_web . '" target="_blank">' . $rec->link_web . '</a>';
                        })
                        ->addColumn('action', function($rec) {
                            $str = '
                          <button type="button" class="btn btn-edit btn-warning" data-id="' . $rec->id . '">จัดการ</button>
                          <button type="button" class="btn btn-delete btn-danger" data-id="' . $rec->id . '" data-name="' . $rec->name . '">ลบ</button>   
                            ';
                            return $str;
                        })->rawColumns(['action', 'manage_ads', 'link_web', 'service_end_date'])->make(true);
    }

    public function get_datatable_no_service() {
        $result = CustomerGoogleAds::where('status', '=', 'ไม่ต่อบริการ')->select();
        return \DataTables::of($result)
                        ->addIndexColumn()
                        ->editColumn('service_end_date', function($rec) {
                            if ($rec->service_end_date != null) {
                                if (date('Y-m-d H:i:s', strtotime("-3 day", strtotime($rec->service_end_date))) < date('Y-m-d H:i:s')) {
                                    return '<span style="padding: 3px; 3px;border: 3px solid red;">' . DateThai($rec->service_end_date) . '</span>';
                                } else {
                                    return DateThai($rec->service_end_date);
                                }
                            } else {
                                return '';
                            }
                        })
                        ->editColumn('expired_web_date', function($rec) {
                            if ($rec->expired_web_date != null) {
                                return DateThai($rec->expired_web_date);
                            } else {
                                return '';
                            }
                        })
                        ->editColumn('price', function($rec) {
                            return number_format($rec->price);
                        })
                        ->editColumn('manage_ads', function($rec) {
                            return '<button type="button" class="btn btn-info"><a style="color:white;" href="' . $rec->manage_ads . '" target="_blank">ดูโฆษณา</a></button>';
                        })
                        ->editColumn('link_web', function($rec) {
                            return '<a href="' . $rec->link_web . '" target="_blank">' . $rec->link_web . '</a>';
                        })
                        ->addColumn('action', function($rec) {
                            $str = '
                          <button type="button" class="btn btn-edit btn-warning" data-id="' . $rec->id . '">จัดการ</button>
                          <button type="button" class="btn btn-delete btn-danger" data-id="' . $rec->id . '" data-name="' . $rec->name . '">ลบ</button>   
                            ';
                            return $str;
                        })->rawColumns(['action', 'manage_ads', 'link_web', 'service_end_date'])->make(true);
    }

}

<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\WorkTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkTimeController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth", "hasAccess"]);
    }

    public function index()
    {
        $flag = 'sitting';
        $WorkTimes = WorkTime::all();
        return view('panel.dashboard.settings.workTime.workTime', compact('flag', 'WorkTimes'));
    }

    public function edit(Request $request)
    {
        $flag = 'sitting';
        $validate = Validator::make($request->all(), [
            'id' => 'required|exists:work_times,id',
        ], [
            'id.required' => 'حصل خطا , حاول مرة اخرى',
            'id.exists' => 'حصل خطا ,حاول مرة اخرى'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate->errors());
        }

        $WorkTime = WorkTime::find($request->id);
        if ($WorkTime) {
            return view('panel.dashboard.settings.workTime.edit', compact('flag', 'WorkTime'));
        }
    }
    public function update(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'id' => 'required|exists:work_times,id',
                'from_time' => 'required|date_format:H:i',
                'to_time' => 'required|date_format:H:i',
            ], [
                'id.required' => 'معرف وقت الدوام مطلوب.',
                'id.exists' => 'معرف وقت الدوام غير موجود في قاعدة البيانات.',
                'from_time.required' => 'وقت بداية الدوام مطلوب.',
                'from_time.date_format' => 'يجب أن يكون وقت البداية بصيغة ساعة ودقيقة (مثال: 09:00).',
                'to_time.required' => 'وقت نهاية الدوام مطلوب.',
                'to_time.date_format' => 'يجب أن يكون وقت النهاية بصيغة ساعة ودقيقة (مثال: 17:00).',
            ]);
            
            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate->errors());
            }
            $workTime = WorkTime::find($request->id);
            if ($workTime) {
                $workTime->from_time = $request->from_time;
                $workTime->to_time = $request->to_time;
                if ($workTime->save()) {
                    return redirect()->route("work.times.show")->with('success', 'تم التعديل بنجاح');
                } else {
                    return redirect()->back()->with('error', 'حصل خطا, حاول مرة اخرى');
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function delete (Request $request) {
        try {
            $validate = Validator::make($request->all(), [
                'id' => 'required|exists:work_times,id',
            ], [
                'id.required' => 'معرف وقت الدوام مطلوب.',
                'id.exists' => 'معرف وقت الدوام ��ير موجود في قاعدة البيانات.',
            ]);

            if ($validate->fails()) {
                return redirect()->back()->withErrors($validate->errors());
            }
            $workTime = WorkTime::find($request->id);
            if ($workTime) {
                $workTime->from_time = '' ;
                $workTime->to_time = '' ;
                if ($workTime->save()) {
                    return redirect()->route("work.times.show")->with('success', 'تمت العملية بنجاح');
                } else {
                    return redirect()->back()->with('error', 'حصل خطا, حاول مرة اخرى');
                }
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    } 
    
}

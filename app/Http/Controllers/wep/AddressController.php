<?php

namespace App\Http\Controllers\wep;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validation = Validator::make(
            ['id' => $request->id],
            [
                'id' => ['required', Rule::exists('users', 'id')->where('type', 'client')]
            ],
            [
                'id.required' => 'حصل خطا , حاول مرة اخرى',
                'id.exists' => 'حصل خطا , حاول مرة اخرى',
            ]
        );
        if ($validation->fails()) {
            return redirect()->back()->with("error", "حصل خطا, حاول مرة اخرى");
        }
        try {
            $client = User::where('type' , 'client')->where('id', $request->id)->first();
            return view('panel.dashboard.addresses.add', compact('client'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validation = Validator::make($request->all(), [
            'id' => ['required', Rule::exists('users', 'id')->where('type', 'client')],
            'title' => ['required', 'string'],
        ], [
            'id.required' => 'حصل خطا , حاول مرة اخرى',
            'id.exists' => 'حصل خطا , حاول مرة اخرى',

            'title.required' => "العنوان مطلوب",
            'title.string' => "العنوان مطلوب",
        ]);
        if ($validation->fails()) {
            return redirect()->back()->withErrors($validation)->withInput($request->all());
        }
        try {
            $address = new Address();
            $address->client_id = $request->id;
            $address->title = $request->title;
            $address->save();
            return redirect()->route("clients.show" , ['id'=>$request->id]);
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $validation = Validator::make($request->id, ['id' => 'required|exists:addresses,id']);
        if ($validation->fails()) return redirect()->back()->with("error", "حصل خطا , حاول مرة اخرى");

        try {
            $addresses = Address::find($request->id);
            if ($addresses) {
                return view('panel.dashboard.addresses.edit');
            } else {
                return redirect()->back()->with('error', 'حصل خطأ , حاول مرة اخرى');
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

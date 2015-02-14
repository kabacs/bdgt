<?php namespace Bdgt\Http\Controllers;

use Bdgt\Resources\Bill;

use Input;

class BillController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $bills = Bill::all();

        $bills->sortBy(function ($bill) {
            return $bill->nextDue;
        });

        $c['bills'] = $bills;

        return view('bill/index', $c);
    }

    public function show($id)
    {
        try {
            $bill = Bill::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect('/bills');
        }

        $c['bill'] = $bill;

        return view('bill/show', $c);
    }

    public function store()
    {
        if ($bill = Bill::create(Input::all())) {
            session()->flash('alerts.success', 'Bill created');
            return redirect("/bills/{$bill->id}");
        } else {
            session()->flash('alerts.danger', 'Bill creation failed');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        if (Bill::where('id', '=', $id)->delete()) {
            session()->flash('alerts.success', 'Bill deleted');
            return redirect("/bills");
        } else {
            session()->flash('alerts.danger', 'Bill deletion failed');
            return redirect()->back();
        }
    }
}

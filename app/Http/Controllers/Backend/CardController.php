<?php

namespace App\Http\Controllers\Backend;

use App\Models\PaymentCardType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class CardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view card')->only('index');
        $this->middleware('permission:create card')->only(['create', 'store']);
        $this->middleware('permission:update card')->only(['edit', 'update']);
        $this->middleware('permission:delete card')->only('destroy');
    }
    public function create()
    {
        return view("banks.cards.create");
    }
    public function store(Request $request){
        if ($request->isMethod('post')) {
            $card = new PaymentCardType();
            $card->card_type = $request->card_type;
            $card->save();
            return redirect()
                ->route('cards.create')
                ->with('flash_success', $this->toastMessage('Card successfully added.'));
        }
    }
    public function index(){
        $cards = PaymentCardType::paginate(100);
        return view("banks.cards.index")->with(compact('cards'));
    }
    public function edit($id){
        $card = PaymentCardType::FindOrFail($id);
        return view("banks.cards.edit")->with(compact('card'));
    }
    public function update(Request $request, $id){
        $card = PaymentCardType::FindOrFail($id);
        $card->card_type = $request->card_type;
        $card->save();
        return redirect()
            ->route('cards.index')
            ->with('flash_success', $this->toastMessage('Card successfully updated.'));
        
    }
    public function destroy($id)
    {
        if (!empty($id)) {
            $data = PaymentCardType::FindOrFail($id);
            PaymentCardType::find($id)->delete();
            return redirect()
                ->route('cards.index')
                ->with('flash_success', $this->toastMessage('Card successfully delete.', 'warning'));
        }
    }
}

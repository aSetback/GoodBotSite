<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Raid;
use App\RaidReserve;
use App\ReserveItem;
use App\RaidHash;
use App\Signup;

class GoodBotController extends Controller
{
    public function OAuth() {
        
    }

    public function index($raidName)
    {

        $hash = RaidHash::where('hash', $raidName)->first();
        if (!$hash) {
            abort(404);
        }
        $raids = Raid::where('memberID', $hash->memberID)
            ->where('guildID', $hash->guildID)
            ->where('date', '>', date('Y-m-d, h:i:s'))
            ->where('date', '<', date('Y-m-d, h:i:s', strtotime("+3 Months")))
            ->get();
        return view('goodbot.index')
            ->with('raidName', $raidName)
            ->with('raids', $raids);
    }

    public function signups($id)
    {
        $raid = Raid::findOrFail($id);
        $hash = RaidHash::where('memberID', $raid->memberID)
            ->where('guildID', $raid->guildID)
            ->first();
        if (!$hash) {
            abort(404);
        }
        $signups = Signup::where('raidID', $id)->get();
        return view('goodbot.signup')
            ->with('hash', $hash)
            ->with('raid', $raid)
            ->with('signups', $signups);
    }

    public function reserves($id)
    {
        $raid = Raid::with(['signups', 'signups.reserve', 'signups.reserve.item'])->where('id', $id)->first();
        $signups = $raid->signups->sortBy(function($signup) {
            if (!$signup->reserve) {
                return 0;
            }
            return $signup->reserve->item->name;
        });
        $hash = RaidHash::where('memberID', $raid->memberID)
            ->where('guildID', $raid->guildID)
            ->first();
        if (!$hash || !$raid) {
            abort(404);
        }

        $items = ReserveItem::where('raid', $raid->raid)->orderBy('name')->get();
        
        return view('goodbot.reserves')
        ->with('hash', $hash)
        ->with('raid', $raid)
        ->with('items', $items)
        ->with('signups', $signups);
    }

    public function reserve($signupID, $itemID) {
        $signup = Signup::findOrFail($signupID);
        RaidReserve::updateOrCreate(
            ['signupID' => $signupID, 'raidID' => $signup->raidID],
            ['reserveItemID' => $itemID]
        );
        return redirect()->route('reserves', ['id' => $signup->raidID]);
    }
}
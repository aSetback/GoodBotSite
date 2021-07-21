<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Log;
use App\Raid;
use App\Setting;
use App\Guild;

class DashboardController extends Controller
{
    public function index() {
        $servers = session()->get('guilds');
        return view('dashboard.index')
            ->with('servers', $servers);
    }

    public function dashboard($serverID) {
        $currentServer = $this->getServer($serverID);
        $this->goodBotInstalled($serverID);        
        return view('dashboard.dashboard')
            ->with('server', $currentServer);
    }

    public function admin() {
        if (!env('dashboard_key') || request()->query('key') != env('dashboard_key')) { abort(404); }
        $logs = Log::orderBy('createdAt', 'DESC')->limit(100)->get();
        $raids = Raid::orderBy('createdAt', 'DESC')->limit(20)->get();
        $guilds = Guild::orderBy('createdAt', 'DESC')->limit(20)->get();
        return view('dashboard.admin')
            ->with('logs', $logs)
            ->with('raids', $raids)
            ->with('guilds', $guilds);
    }

    public function logs($serverID) {
        $servers = session()->get('guilds');
        $currentServer = null;
        foreach ($servers AS $server) {
            if ($server->id == $serverID) {
                $currentServer = $server;
            }
        }
        if (empty($currentServer)) {
            abort(404);
        }

        if ($currentServer->permissions != 2147483647) {
            abort(403);
        }

        $page = request()->query('p', 1);

        $logs = Log::where('guildID', $serverID)->orderBy('createdAt', 'DESC')->limit(100)->offset(($page - 1) * 100)->get();
        foreach ($logs AS &$log) {
            $log->event = explode('/', $log->event);
        }

        return view('dashboard.logs')
            ->with('server', $currentServer)
            ->with('logs', $logs)
            ->with('page', $page);
    }

    
    public function settings($serverID) {
        $currentServer = $this->getServer($serverID);

        $settings = Setting::where('guildID', $serverID)->first();
        if (empty($settings)) {
            $settings = new Setting;
        }

        $euServerList = ["Amnennar", "Ashbringer", "Auberdine", "Bloodfang", "Celebras", "Chromie", "Dragon's Call", "Dragonfang", "Dreadmist", "Earthshaker", "Everlook", "Finkle", "Firemaw", "Flamegor", "Flamelash", "Gandling", "Gehennas", "Golemagg", "Harbinger of Doom", "Heartstriker", "Hydraxian Waterlords", "Judgement", "Lakeshire", "Lucifron", "Mandokir", "Mirage Raceway", "Mograine", "Nethergarde Keep", "Noggenfogger", "Patchwerk", "Pyrewood Village", "Razorfen", "Razorgore", "Rhok'delar", "Shazzrah", "Skullflame", "Stonespine", "Sulfuron", "Ten Storms", "Transcendence", "Venoxis", "Wyrmthalak", "Zandalar Tribe"];
        $naServerList = ["Anathema", "Arcanite Reaper", "Arugal", "Ashkandi", "Atiesh", "Azuresong", "Benediction", "Bigglesworth", "Blaumeux", "Bloodsail Buccaneers", "Deviate Delight", "Earthfury", "Faerlina", "Fairbanks", "Felstriker", "Grobbulus", "Heartseeker", "Herod", "Incendius", "Kirtonos", "Kromcrush", "Kurinnaxx", "Loatheb", "Mankrik", "Myzrael", "Netherwind", "Old Blanchy", "Pagle", "Rattlegore", "Remulos", "Skeram", "Smolderweb", "Stalagg", "Sul'thraze", "Sulfuras", "Thalnos", "Thunderfury", "Westfall", "Whitemane", "Windseeker", "Yojamba"];

        return view('dashboard.settings')
            ->with('settings', $settings)
            ->with('server', $currentServer)
            ->with('naServerList', $naServerList)
            ->with('euServerList', $euServerList);
    }

    public function postSettings(Request $request, $serverID) {
        $server = $this->getServer($serverID);
        Setting::updateOrCreate(
            ['guildID' => $serverID],
            [
                'faction' => $request->faction,
                'server' => $request->wowServer,
                'region' => $request->region,
                'sheet' => $request->sheetID
            ]
        );
        return redirect()->route('dashboard', [$serverID]);
    }

    public function setup($serverID) {
        $server = $this->getServer($serverID);
        $channels = $this->botRequest('/guilds/' . $serverID . '/channels');
        if (is_object($channels)) {
            abort('404');
        }
        $euServerList = ["Amnennar", "Ashbringer", "Auberdine", "Bloodfang", "Celebras", "Chromie", "Dragon's Call", "Dragonfang", "Dreadmist", "Earthshaker", "Everlook", "Finkle", "Firemaw", "Flamegor", "Flamelash", "Gandling", "Gehennas", "Golemagg", "Harbinger of Doom", "Heartstriker", "Hydraxian Waterlords", "Judgement", "Lakeshire", "Lucifron", "Mandokir", "Mirage Raceway", "Mograine", "Nethergarde Keep", "Noggenfogger", "Patchwerk", "Pyrewood Village", "Razorfen", "Razorgore", "Rhok'delar", "Shazzrah", "Skullflame", "Stonespine", "Sulfuron", "Ten Storms", "Transcendence", "Venoxis", "Wyrmthalak", "Zandalar Tribe"];
        $naServerList = ["Anathema", "Arcanite Reaper", "Arugal", "Ashkandi", "Atiesh", "Azuresong", "Benediction", "Bigglesworth", "Blaumeux", "Bloodsail Buccaneers", "Deviate Delight", "Earthfury", "Faerlina", "Fairbanks", "Felstriker", "Grobbulus", "Heartseeker", "Herod", "Incendius", "Kirtonos", "Kromcrush", "Kurinnaxx", "Loatheb", "Mankrik", "Myzrael", "Netherwind", "Old Blanchy", "Pagle", "Rattlegore", "Remulos", "Skeram", "Smolderweb", "Stalagg", "Sul'thraze", "Sulfuras", "Thalnos", "Thunderfury", "Westfall", "Whitemane", "Windseeker", "Yojamba"];

        return view('dashboard.setup')
            ->with('channels', $channels)
            ->with('server', $server)
            ->with('naServerList', $naServerList)
            ->with('euServerList', $euServerList);
    }

    public function setupSave($serverID) {
        $server = $this->getServer($serverID);
        $channels = $this->botRequest('/guilds/' . $serverID . '/channels');

        $settings = [];
        if (request()->query('server')) {
            $server = request()->query('server');
            $serverParts = explode('/', $server);
            $settings['server'] = $serverParts[1];
            $settings['region'] = $serverParts[0];
        }

        if (request()->query('expansion')) {
            $settings['expansion'] = request()->query('expansion');
        }

        if (request()->query('faction')) {
            $settings['faction'] = request()->query('faction');
        }

        if (request()->query('raidCategory')) {
            $raidCategory = null;
            foreach ($channels AS $channel) {
                if ($channel->id == request()->query('raidCategory')) {
                    $raidCategory = $channel->name;
                    break;
                }
            }
            if (!empty($raidCategory)) {
                $settings['raidcategory'] = $raidCategory;
            }
        }

        if (!empty($settings)) {
            Setting::updateOrCreate(
                ['guildID' => $serverID],
                $settings
            );
        }

        if (request()->query('setup') == 'Yes') {
            $this->sendMessage($serverID, 0, '+setup');
        }

        return redirect()->route('dashboard', [$serverID]);
    }

    public function install($serverID) {
        $currentServer = $this->goodBotInstalled($serverID);
        return view('dashboard.install')
            ->with('server', $currentServer);
    }

}
@extends('layouts.dashboard')

@section('content')

<script>const whTooltips = {"iconizeLinks": false, "hide": { "droppedby": true, "dropchance": true}};</script>
<script src="https://wow.zamimg.com/widgets/power.js"></script>
<div id="gear-modal">
    <a id="gear-modal-close" onclick="closeGearModal();">x</a>
    <div id="gear-div">
        <div id="gear-side">
            <a class="gear-box" slot="Head"></a>
            <a class="gear-box" slot="Neck"></a>
            <a class="gear-box" slot="Shoulders"></a>
            <a class="gear-box" slot="Cloak"></a>
            <a class="gear-box" slot="Chest"></a>
            <a class="gear-box" slot="Shift"></a>
            <a class="gear-box" slot="Tabard"></a>
            <a class="gear-box" slot="Bracers"></a>
        </div>
        <div id="gear-middle">
            <div class="gear-player">
                Player
            </div>
            <div class="gear-lastseen">
                Last Seen
            </div>
            <div class="gear-loading">
                Loading<br />
                <img src="/assets/img/loading.gif" />
                <br />
            </div>
            <div class="gear-bottom">
                <a class="gear-box" slot="Main Hand"></a>
                <a class="gear-box" slot="Off Hand"></a>
                <a class="gear-box" slot="Ranged"></a>
            </div>
        </div>
        <div id="gear-side">
            <a class="gear-box" slot="Gloves"></a>
            <a class="gear-box" slot="Belt"></a>
            <a class="gear-box" slot="Legs"></a>
            <a class="gear-box" slot="Boots"></a>
            <a class="gear-box" slot="Ring1"></a>
            <a class="gear-box" slot="Ring2"></a>
            <a class="gear-box" slot="Trinket1"></a>
            <a class="gear-box" slot="Trinket2"></a>
        </div>
    </div>
</div>
<section class="wrapper style2 container special-alt">
    <div class="container gb-lineup-top">
        <h2>Manage Lineup</h2>
        <h4>{{ $raid->name }}<h4>
        <h4>{{ date('F d, Y', strtotime($raid->date)) }}</h4>
        <a href="/raids/{{ $raid->id }}/manage">Edit Raid &rarr;</a><br />
    </div>
    <div class="container">
        @include('raids/_partials/signup', ['role' => 'tank', 'label' => 'Tanks', 'signup' => 'yes'])
        @include('raids/_partials/signup', ['role' => 'healer', 'label' => 'Healers', 'signup' => 'yes'])
        @include('raids/_partials/signup', ['role' => 'caster', 'label' => 'Casters', 'signup' => 'yes'])
        @include('raids/_partials/signup', ['role' => 'dps', 'label' => 'DPS', 'signup' => 'yes'])
    </div>
    <div class="container gb-lineup-bottom">
        <i>New confirmations will not show in the sign-up channel until the embed is refreshed.<br />
            This can be done via the button, another playing signing up, or a raid setting being changed.</i>
        <br />
        <a href="/raids/{{ $raid->id }}/command/refresh">
            <button>Refresh Channel</button>
        </a> 
    </div>
</section>

@endsection

@section('scripts')
    <script>
        function playerInfo(el, mainID) {
            $.ajax({
                url: '/raids/{{ $raid->id }}/character/' + mainID,
                success: function(data) {
                    $row = $('<tr main="' + mainID + '"></tr>');
                    $(el).parent().after($row);
                    $row.append('<td colspan=5><table class="no-padding" /></td>');
                    $table = $row.find('table');
                    $header = $('<tr>');
                    $header.append('<td width="5%" />');
                    $header.append('<td width="15%">Name</td>');
                    $header.append('<td width="15%">Class</td>');
                    $header.append('<td width="15%">Role</td>');
                    $header.append('<td width="20%">Signups</td>');
                    $header.append('<td width="20%">No Shows</td>');
                    $header.append('<td width="10%" class="align-right"><span onclick="deleteRow(this);" class="icon solid fa-ban"></span></td>');
                    $table.append($header);
                    data.forEach((character, key) => {
                        $childRow = $('<tr class="info-row"></tr>');
                        if (key == 0)
                            $childRow.append('<td width="5%">*</td>');
                        else
                            $childRow.append('<td width="5%"></td>');
                        $childRow.append('<td width="15%">' + character.name + '</td>');
                        $childRow.append('<td width="15%"><i class="color-' + character.class + '">' + character.ucclass + '</i></td>');
                        $childRow.append('<td width="15%">' + character.ucrole + '</td>');
                        $childRow.append('<td width="20%">' + character.stats.signups + '</td>');
                        $childRow.append('<td width="20%">' + character.stats.noshows + '</td>');
                        $childRow.append('<td width="10%" />');
                        $table.append($childRow);
                    });
                }
            })
        }
        function deleteRow(el) {
            $(el).parents('tr').remove();
        }
        function addConfirm(signupID) {
            var row = $('[signup=' + signupID + ']');
            $.ajax({
                url: '/raids/{{ $raid->id }}/confirm/' + signupID,
                success: function(data) {
                    row.find('strong').html('Confirmed').removeClass('red').addClass('green');
                }
            });
        }
        function removeConfirm(signupID) {
            $.ajax({
                url: '/raids/{{ $raid->id }}/unconfirm/' + signupID,
                success: function(data) {
                    row.find('strong').html('Unconfirmed').removeClass('green').addClass('red');
                }
            })
            var row = $('[signup=' + signupID + ']');
        }

        function gearCheck(player) {
            $('.gear-loading').show();
            $('.gear-lastseen, .gear-player').hide();
            $('.gear-box').css('background-image', '');
            $('#gear-modal').show();
            $.ajax({
                url: '/gear/' + player + '/Mankrik/US',
                success: function(data) {
                    $('.gear-loading').hide();
                    $('.gear-player').html(player).show();
                    for (slot in data.items) {
                        try {
                            if (slot == "Rings") {
                                loadItem($('[slot="Ring1"]'), Object.values(data.items[slot])[0]);
                                loadItem($('[slot="Ring2"]'), Object.values(data.items[slot])[1]);
                            } else if (slot == "Trinkets") {
                                loadItem($('[slot="Trinket1"]'), Object.values(data.items[slot])[0]);
                                loadItem($('[slot="Trinket2"]'), Object.values(data.items[slot])[1]);
                            } else {
                                loadItem($('[slot="' + slot + '"]'), Object.values(data.items[slot])[0]);
                            }
                        } catch (e) {

                        }
                    }
                    $('.gear-lastseen').html('Last Seen: ' + data.date).show();
                }
            })
        }
        function loadItem(element, item) {
            if (item.permanentEnchant) {
                element.attr('href', 'https://wotlk.wowhead.com/item=' + item.id + '&ench=' + item.permanentEnchant);
            } else {
                element.attr('href', 'https://wotlk.wowhead.com/item=' + item.id);
            }
            element.css('background-image', 'url(https://wow.zamimg.com/images/wow/icons/large/' + item.icon + ')');
            element.attr('quality', item.quality);
        }
        function closeGearModal() {
            $('#gear-modal').hide();
        }
    </script>
@endsection
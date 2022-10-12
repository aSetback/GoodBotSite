@extends('layouts.app')

@section('content')
<script>const whTooltips = {colorLinks: true, iconizeLinks: true, renameLinks: true};</script>
<script src="https://wow.zamimg.com/widgets/power.js"></script>
<header class="special container">
    <span class="icon solid fa-clipboard-check"></span>
    <h2>Raid Reserves<br /> {{ $raid->name ? $raid->name . ' ' . $raid->raid: $raid->raid}}<br />{{ $raid->date }} </h2>
</header>
<section class="wrapper style2 container special-alt">
<div class="container">
    <div class="row justify-content-center">
        <table id="reserves">
            <thead>
                <tr>
                    <th style="wdith: 200px;">Name</th>
                    <th style="width: 400px;">Reserve</th>
                    <th>
                </tr>
            </thead>
        <tbody>
        @foreach ($signups AS $signup) 
            @if ($signup->signup == 'yes')
                <tr signup="{{ $signup->id }}">
                    <td>{{ $signup->player }}</td>
                    <td>
                        @if ($signup->reserve)
                            <a href="https://wotlk.wowhead.com/item/{{ $signup->reserve->item->itemID }}" id="reserve-link-{{ $signup->id }}">{{ $signup->reserve->item->name }}</a>
                        @else
                            <a id="reserve-link-{{ $signup->id }}">none</a>
                        @endif
                        <select class="reserve-select" id="reserve-select-{{ $signup->id }}" onchange="saveReserve(this.value, {{ $signup->id }});">
                            <option value="0">None</option>
                            @foreach ($items AS $item)
                                <option value="{{ $item->id }}"
                                    @if ($signup->reserve && $signup->reserve->item->id == $item->id)
                                        selected
                                    @endif
                                >{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <a onclick="showSelect({{ $signup->id }});">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </td>
                </tr>
            @endif
        @endforeach
        </tbody>
        </table>
        <br /><br />
    </div>
</div>
</section>
@endsection

@section('scripts')
<script>
    $(function(){
        $('#reserves').tablesorter({
            sortList: [[1,0], [0,0]]
        }); 
    });
    function saveReserve(itemID, signupID) {
        window.location = '/reserve/' + signupID + '/' + itemID;
    }
    function showSelect(signupID) {
        var row = $('[signup=' + signupID + ']');
        var icon = row.find('i');
        if (icon.hasClass('fa-pencil')) {
            row.find('i').removeClass('fa-pencil').addClass('fa-ban');
            $('#reserve-select-' + signupID).show();
            $('#reserve-link-' + signupID).hide();
        } else {
            row.find('i').removeClass('fa-ban').addClass('fa-pencil');
            $('#reserve-select-' + signupID).hide();
            $('#reserve-link-' + signupID).show();
        }
    }
</script>
@endsection
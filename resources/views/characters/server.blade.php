@extends('layouts.dashboard')

@section('content')
<div id="modal-overlay" onclick="hideModal();">
</div>
<div id="modal">
    <form method="POST" action="">
        <input type="hidden" id="characterID" name="characterID" value="" />
        <label for="characterName">Character</label>
        <input class="input-name" value="" id="characterName" name="characterName" />
        <label for="characterClass">Class</label>
        <select id="characterClass" name="characterClass">
            @foreach ($classes AS $class)
                <option value="{{ $class }}">{{ $class }}</option>
            @endforeach
        </select>
        <label for="characterRole">Role</label>
        <select id="characterRole" name="characterRole">
            @foreach ($roles AS $role)
                <option value="{{ $role }}">{{ $role }}</option>
            @endforeach
        </select>
        <input type="submit" value="Save Character" onclick="saveModal();" />
    </form>
</div>

<section class="wrapper style2 container special-alt">
    @if (empty($characters))
    <div class="container">
        <h2>You don't have a character set up on this server yet, {{ $nick }}!</h2>
        <h4>Let's fix that!</h4>
        <div class="gb-row">
            <label>What is your main's name?</label>
            <input name="name" value="{{ $nick }}" />
        </div>  
        <div class="gb-row">
            <label>What class is your main?</label>
            <select name="class">
                <option></option>
                @foreach ($classes AS $class)
                    <option value="{{ $class }}">{{ $class }}</option>
                @endforeach
            </select>
        </div>  
        <div class="gb-row">
            <label>What role is your main?</label>
            <select name="role">
                <option></option>
                @foreach ($roles AS $role)
                    <option value="{{ $role }}">{{ $role }}</option>
                @endforeach
            </select>
        </div>  
        <button onclick="addCharacter();">Add Character</button>
    </div>
    @else
    <div class="container">
        <div class="row justify-content-center">
            <table>
                <tr>
                    <th colspan="2" width="30%">Character</th>
                    <th width="25%">Class</th>
                    <th width="25%">Role</th>
                    <th></th>
                </tr>
            @foreach ($characters AS $character) 
                <tr character="{{ $character->id }}">
                    <td>
                        @if (empty($character->mainID)) 
                            <i class="orange">main</i>
                        @else
                            <i class="red">alt</i>
                        @endif
                    </td>
                    <td class="character-name">
                        <span class="character-name">{{ $character->name }}</span>
                    </td>
                    <td class="character-class">
                        <span class="character-class color-{{ $character->class }}">{{ $character->class }}</span>
                    </td>
                    <td class="character-role">
                        <span class="character-role">{{ $character->role }}</span>
                    </td>
                    <td class="align-right">
                        <a onclick="editCharacter({{ $character->id }});">
                            <i class="fa fa-pencil"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            <tr character="0">
                <td colspan="5" align="right">
                    <a onclick="addNew();"><i class="fa fa-plus green"> <span>Add Character</span></i> </a>
                </td>   
            </tr>
            </table>
        </div>
    </div>
    @endif
</section>
@endsection

@section('scripts')
<script>
    function addNew() {
        openModal();
    }

    function hideModal() {
        $('#modal').hide();
        $('#modal-overlay').fadeOut(500);
    }

    function editCharacter(characterID) {
        var row = $('tr[character=' + characterID + ']');
        var character = {
            id: characterID,
            name: row.find('.character-name span').html(),
            class: row.find('.character-class span').html(),
            role: row.find('.character-role span').html()
        }
        openModal(character);
    }

    function openModal(character) {
        $('#modal-overlay').fadeIn(500, function() {
            $('#modal').show();
        });
        if (character) {
            $('#characterID').val(character.id);
            $('#characterName').val(character.name);
            $('#characterClass').val(character.class);
            $('#characterRole').val(character.role);
        } else {
            $('#characterID').val(0);
            $('#characterName').val('');
            $('#characterClass').val(0);
            $('#characterRole').val(0);
        }
    }

    function saveModal() {
        var character = {
            id: $('#characterID').val(),
            name: $('#characterName').val(),
            class: $('#characterClass').val(),
            role: $('#characterRole').val(),
        }
        $.ajax({
            url: '/characters/save/{{ $server->id }}/' + character.id,
            data: character,
            success: function(data) {
                if (data.success) {
                    location.reload();
                }
            }
        });
    
    }
</script>
@endsection

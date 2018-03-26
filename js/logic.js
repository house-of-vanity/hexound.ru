var player;
var interval;
var playlist = [];
var fileaccess = document.querySelector('*');
var current_mod;
var mode = "direct_order";
var VERSION = 0.07


function init() {
    if (player == undefined) {
        player = new ChiptuneJsPlayer(new ChiptuneJsConfig(0));
        setInterval(progress, 200);
    }
    else {
        player.stop();
        playPauseButton();
    }
}

function getRandomInt(max) {
  min = 0;
  max = Math.floor(max);
  return Math.floor(Math.random() * (max - min)) + min;
}

function start_play(id, action) {
    document.getElementById("mod_"+current_mod).style.color = "#4c4cbd";
    if (action == "user") {
        var result = $.grep(playlist, function(e){ return e.id == id; });
        console.log(result[0].name);
        console.log("Currently playing: "+current_mod)
        document.getElementById("mod_"+current_mod).style.color = "#4c4cbd";
        id = result[0].id;
        name = result[0].name;
        console.log('PLaying ' + name + ". Initiated by User.");
        loadURL('/mods/' + name);
        document.getElementById('filename').innerHTML = name
        window.location.hash = '#' + (id);
        document.getElementById("mod_" + id).style.color = "#b30059";
        current_mod = id;
    } else {
        if (check_mode() == "shuffle") {

            var next = getRandomInt(playlist.length);
            console.log('PLaying ' + playlist[next].name + ". Initiated by System. Shuffle");
            loadURL('/mods/' + playlist[next].name);
            document.getElementById('filename').innerHTML = playlist[next].name
            window.location.hash = '#' + playlist[next].id
            document.getElementById("mod_" + playlist[next].id).style.color = "#b30059";
            current_mod = next;
        } else if (check_mode() == "direct_order") {

            var next = playlist.findIndex(x => x.id == current_mod) + 1;
            id = playlist[next].id;
            name = playlist[next].name;
            console.log('PLaying ' + name + ". Initiated by System. Direct order.");
            loadURL('/mods/' + name);
            document.getElementById('filename').innerHTML = name
            window.location.hash = '#' + id
            document.getElementById("mod_" + id).style.color = "#b30059";
            current_mod = id;
        } else if (check_mode() == "loop") {

            var next = playlist.findIndex(x => x.id == current_mod);
            id = playlist[next].id;
            name = playlist[next].name;
            console.log('PLaying ' + name + ". Initiated by System. Loop.");
            loadURL('/mods/' + name);
            document.getElementById('filename').innerHTML = name
            window.location.hash = '#' + id
            document.getElementById("mod_" + id).style.color = "#b30059";
            current_mod = id;
        }
    }
}


function setMetadata(filename) {
    var metadata = player.metadata();
    
    if (metadata['title'] != '') {
        document.getElementById('title').innerHTML = metadata['title'];
    }
    else {
        document.getElementById('title').innerHTML = filename;
    }

    if (metadata['artist'] != '') {
        document.getElementById('artist').innerHTML = '<br />' + metadata['artist'];
    }
    else {
        document.getElementById('artist').innerHTML = '';
    }
}

function toInt(n) {
    return Math.round(Number(n));
};

function progress() {
    document.getElementById('rangeinput').value = player.getPosition();
    $("#output").html(toInt(player.getPosition()));
    if (toInt(player.getPosition()) == toInt(player.duration()))
        start_play(0, "system")
}

function loadURL(path) {
    clearInterval(interval);
    init();
    player.load(path, function (buffer) {
        player.play(buffer);
        setMetadata(path);
        $("#rangeinput").attr('max', player.duration());
        $("#rangeinput").attr('value', 0);
        $("#duration").html(toInt(player.duration()));
        pausePauseButton();
    });
}

function pauseButton() {
    player.togglePause();
    switchPauseButton();
}

function switchPauseButton() {
    var button = document.getElementById('pause')
    if (button) {
        button.id = "play_tmp";
    }
    button = document.getElementById('play')
    if (button) {
        button.id = "pause";
    }
    button = document.getElementById('play_tmp')
    if (button) {
        button.id = "play";
    }
}

function playPauseButton() {
    var button = document.getElementById('pause');
    if (button) {
        button.id = "play";
    }
}

function pausePauseButton() {
    var button = document.getElementById('play')
    if (button) {
        button.id = "pause";
    }
}


fileaccess.ondrop = function (e) {
    e.preventDefault();
    var file = e.dataTransfer.files[0];
    var fd = new FormData();
    fd.append("files", file);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'handle_file_upload.php', true);
    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            var percentComplete = (e.loaded / e.total) * 100;
            console.log(percentComplete + '% uploaded');
        }
    };
    xhr.onload = function () {
        if (this.status == 200) {
            var resp = JSON.parse(this.response);
            console.log('Server got:', resp);
        };
    };
    xhr.send(fd);
    init();

    player.load(file, function (buffer) {
        player.play(buffer);
        setMetadata(file.name);
        pausePauseButton();
    });
    get_files();
}

fileaccess.ondragenter = function (e) { e.preventDefault(); }
fileaccess.ondragover = function (e) { e.preventDefault(); };

function get_files() {
    $.getJSON('library.php', function (data) {
        $('#list').append('<ul style="list-style-type: none;margin: 0;padding: 0;">')
        $.each(data, function (key, val) {
            //if(val.time == undefined){val.time = 1493299893}
            var tmp = {
                'name'  : val.filename,
                'id'    : val.id
                //'uploaded': val.time
            };
            $('#list').append('<li style="list-style-type: none;" class="thumb selectable arrow light" ><span class="song name" onclick="start_play('+val.id+',\'user\')"><a href="mods/' + val.filename + '"><img style="height:1em;" src="/img/save1.png"></a> <span id="mod_' + val.id + '" class="name_song">' + val.filename + '</span></span></li>');
            console.log(key, val.id);
            tmp
            playlist.push(tmp);
        });
        console.log('Summary:', playlist.length, 'tracks.')
        current_mod = playlist.length - 1;
        $('#list').append('</ul>')

        var mod_number = parseInt(window.location.hash.substr(1)); 
        if (window.location.hash.substr(1) != "") {
            start_play(mod_number,"user")
            console.log("Playing from url mod # " + window.location.hash.substr(1));
        }
    });

$(window).on('hashchange', function() {
    var mod_number = parseInt(window.location.hash.substr(1)); 
    if (window.location.hash.substr(1) != "") {
        start_play(mod_number,"user")
        console.log("Playing from url mod # " + window.location.hash.substr(1));
    }
});

}
get_files();
window.onload = function up() {
    var input = document.getElementById('input');
    document.getElementById('version').innerHTML =VERSION;
    input.onkeyup = function () {
        var filter = input.value.toUpperCase();
        var lis = document.getElementsByTagName('li');
        for (var i = 0; i < lis.length; i++) {
            var name = lis[i].getElementsByClassName('name_song')[0].innerHTML;
            //if (name.toUpperCase().indexOf(filter) == 0)
            if (name.toUpperCase().includes(filter) == true) {
                lis[i].style.display = 'list-item'
            }
            else
                lis[i].style.display = 'none';
        }
    };
}

function check_mode()
{
    var inp = document.getElementsByName('playmode');
    for (var i = 0; i < inp.length; i++) {
        if (inp[i].type == "radio" && inp[i].checked) {
            return inp[i].value;
        }
    }
}
// main.js

function goToSignup() {
    document.location = window.location.protocol + '//' + window.location.host + '/signup';
}


function doLogin() {
    var user = document.getElementById('username').value;
    var plaintext = JSON.stringify({
        username: user,
        password: document.getElementById('password').value
    });
    $.ajax({
        type: "POST",
        url: "/api/login",
        contentType: "application/json",
        data: JSON.stringify({
            payload: encrypt(plaintext)
        }),
        success: function(data) {
            var response = JSON.parse(decrypt(data['payload']));
            if (response['status'] == 'Success') {
                alert(response['message']);
                localStorage['token'] = response['token'];
                localStorage['username'] = user; 
                document.location = window.location.protocol + "//" + window.location.host + "/dashboard";
            }
            else {
                alert(response['message']);
            }
        },
        error: function(jqXhr, textStatus, errorThrown) { console.log(errorThrown); }
    });
}


function doRegister() {
    var user = document.getElementById('username').value;
    var pass = document.getElementById('password').value;
    var cpass = document.getElementById('cpassword').value;
    if (pass != cpass) {
        alert("Passwords do not match");
        return;
    }
    var plaintext = JSON.stringify({
        email: document.getElementById('email').value,
        username: user,
        name: document.getElementById('name').value,
        password: pass,
        cpassword: cpass
    });
    $.ajax({
        type: "POST",
        url: "/api/signup",
        contentType: "application/json",
        data: JSON.stringify({
            payload: encrypt(plaintext)
        }),
        success: function(data) {
            var response = JSON.parse(decrypt(data['payload']));
            if (response['status'] == 'Success') {
                alert(`${user} Registered Successfully!`);
                document.location = window.location.protocol + "//" + window.location.host;
            }
            else {
                alert(response['message']);
            }
        },
        error: function(jqXhr, textStatus, errorThrown) { console.log(errorThrown); }
    });
}

function doLogout() {
    var user = localStorage['username'];
    var plaintext = JSON.stringify({
        token: localStorage['token'],
        username: user
    });
    $.ajax({
        type: "POST",
        url: "/api/logout",
        contentType: "application/json",
        data: JSON.stringify({
            payload: encrypt(plaintext)
        }),
        success: function(data) {
            console.log(data);
            var response = JSON.parse(decrypt(data['payload']));
            console.log(response);
            if (response['status'] == 'Success') {
                localStorage.clear();
                alert(`${user} Logged out Successfully!`);
                document.location = window.location.protocol + "//" + window.location.host;
            }
        },
        error: function(jqXhr, textStatus, errorThrown) { console.log(errorThrown); }
    });
}


function getFlag() {
    var plaintext = JSON.stringify({
        token: localStorage['token'],
        key: document.getElementById('key').value
    });
    $.ajax({
        type: "POST",
        url: "/api/verifyKey",
        contentType: "application/json",
        data: JSON.stringify({
            payload: encrypt(plaintext)
        }),
        success: function(data) {
            var response = JSON.parse(decrypt(data['payload']));
            alert(response['message']);
        },
        error: function(jqXhr, textStatus, errorThrown) { console.log(errorThrown); }
    });
}

function checkSession() {
    if (Object.keys(localStorage).indexOf('token') < 0) {
        alert("Session does not exist");
        document.location = window.location.protocol + "//" + window.location.host;
    }
    else {
        var plaintext = JSON.stringify({
            token: localStorage['token'],
            is_admin: false
        });
        $.ajax({
            type: "POST",
            url: "/dashboard",
            contentType: "application/json",
            data: JSON.stringify({
                payload: encrypt(plaintext)
            }),
            success: function (data) {
                document.write(data);
            },
            error: function(jqXhr, textStatus, errorThrown) { console.log(errorThrown); }
        });
    }
}

var AES_KEY = CryptoJS.enc.Hex.parse("7824262c7bc1df38dcc1ea799faff39fa17d52453ce65040a7280c9141b60267");
var AES_IV = CryptoJS.enc.Hex.parse('f1cd03bba534cd7039708a30d7cd722d');

function encrypt(plaintext) {
    return CryptoJS.AES.encrypt(plaintext, AES_KEY, {iv: AES_IV}).toString();
}

function decrypt(ciphertext) {
    return CryptoJS.AES.decrypt(ciphertext, AES_KEY, {iv: AES_IV}).toString(CryptoJS.enc.Utf8);
}

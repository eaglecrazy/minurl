$('#url').change(function (e) {
    let val = $('#url').val();
    let http = val.indexOf('http://', 0);
    let https = val.indexOf('https://', 0);
    if (http + https === -2){
        $('#url').val('http://' + val);
    }
    $('.invalid-feedback').hide();

});


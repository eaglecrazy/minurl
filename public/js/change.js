$('#url').change(function (e) {
    let val = $('#url').val();
    let http = val.indexOf('http://', 0);
    let https = val.indexOf('https://', 0);
    if (http + https === -2){
        $('#url').val('http://' + val);
    }
    $('#invalid-feedback-url').hide();
});

$('#localdate').change(function (e) {
    $('#invalid-feedback-date').hide();
});

//на случай если некорректно указано время
if($('#expire').prop('checked')){
    $('#localdate').toggleClass('d-none');
}

$('#urlbtn').click(function () {
    $('#url').select();
    document.execCommand("copy");
    if (window.getSelection) {
        window.getSelection().removeAllRanges();
    } else { // старый IE
        document.selection.empty();
    }
    $('#url').blur();
    $('#urlbtn').text('  Copied  ')
});

//Bekijk profiel
$('#tableBeer').collapse({
  toggle: false;
});
$('#tableKroeg').collapse({
  toggle: false;
});
$('#tableBrouwer').collapse({
  toggle: false;
});

//Bekijk proef profielfoto
function readURL(input) {

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $('#imgTest').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

$("#imgInp").change(function() {
  readURL(this);
});

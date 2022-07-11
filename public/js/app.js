function notificacao(text, tipo) {
  $(".alert").addClass('alert-' + tipo);
  $(".alert").text(text);
  setTimeout(function () {
    $(".alert").removeClass('alert-' + tipo);
    $(".alert").empty();
  }, 3000);
}

// DevBox - Aplicação Principal
// jQuery e Fomantic UI carregados via CDN

$(document).ready(function() {
  console.log("DevBox carregado com sucesso!");
  
  // Inicializa componentes Fomantic UI
  $('.ui.dropdown').dropdown();
  $('.ui.modal').modal();
  $('.ui.accordion').accordion();
});

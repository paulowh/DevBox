function openCardModal(cardElement) {
  const cardId = cardElement.getAttribute("data-card-id");
  const title = cardElement.querySelector(".card-title").textContent;

  // Preencher modal
  //   document.getElementById("modal-card-title").value = title;
  //   document.getElementById("modal-card-description").value = "";

  // Reinicializar dropdown do Semantic UI
  $("#modal-card-uc").dropdown("clear");

  // Abrir modal
  //   $("#card-modal").modal("show");
  document.getElementById("card-modal").style.display = "flex";
  carregarDropdownModal();
}

function carregarDropdownModal() {
  fetch("/cards/show")
    .then((response) => {
      if (!response.ok) {
        throw new Error("Erro na requisição: " + response.status);
      }
      return response.json();
    })
    .then((data) => {
      Object.keys(data).forEach((key) => {
        // Transformando os objetos em formato {name, value}
        const values = data[key].map((item) => ({
          name:
            item.nome_curso ||
            item.nome ||
            item.sigla + "- " + item.nome_completo,
          value: item.id,
        }));
        console.log(values);
        // Inicializa o dropdown usando a class
        console.log(key);
        $(".ui.dropdown." + key).dropdown({
          values: values,
        });
      });
    })
    .catch((error) => {
      console.error("Ocorreu um erro:", error);
    });
}
closeCardModal = function () {
  const modal = document.getElementById("card-modal");
  if (modal) {
    modal.style.display = "none";
  }
};

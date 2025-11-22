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
}
closeCardModal = function () {
  const modal = document.getElementById("card-modal");
  if (modal) {
    modal.style.display = "none";
  }
};
